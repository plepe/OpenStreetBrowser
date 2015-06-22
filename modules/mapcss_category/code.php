<?php
global $mapcss_category_cache;
$mapcss_category_cache = array();

register_hook("init", function() {
  add_html_header("<script src='lib/ol4pgm/ol4pgm.js'></script>");
  add_html_header("<link href='lib/ol4pgm/ol4pgm.css' media='all' rel='stylesheet' />");
});

function get_mapcss_category($id) {
  if(!array_key_exists($id, $mapcss_category_cache))
    $mapcss_category_cache[$id] = new mapcss_Category($id);

  return $mapcss_category_cache[$id];
}

class mapcss_Category {
  function __construct($id) {
    $p = explode("/", $id);
    $repo = array_shift($p);
    $pure_id = implode("/", $p);

    $this->repo = get_category_repository($repo);
    $this->pure_id = $pure_id;
    $this->id = $id;

    $this->full_id = id_escape($id);
    $this->script_id = strtr($this->full_id, array(
      '_'       => '__',
      '@'       => '_at_',
    ));
  }

  function title() {
    $data = $this->data();

    if(array_key_exists("info", $data) && array_key_exists("meta", $data['info']) && array_key_exists("title", $data['info']['meta']))
      return $data['info']['meta']['title'];

    return $this->id;
  }

  function data() {
    $ret = array(
      'id' => $this->id,
      'repo' => $this->repo->id,
      'branch' => $this->repo->branch,
      'type' => 'mapcss_category',
    );

    chdir($this->repo->path());

    $ret['content'] = "";
    $f = popen("git show ". shell_escape($this->repo->branch) .":". shell_escape($this->pure_id .".mapcss"), "r");
    while($r = fread($f, 1024))
      $ret['content'] .= $r;
    pclose($f);

    global $data_path;
    $compiled_categories = "{$data_path}/compiled_categories";
    $script = "{$compiled_categories}/{$this->script_id}.py";

    if(!file_exists($script))
      $this->compile();

    if(file_exists($script)) {
      $params = $_SERVER;

      if(array_key_exists('QUERY_STRING', $params))
        $params['QUERY_STRING'] .= "&meta=only";
      else
        $params['QUERY_STRING'] = "meta=only";

      if(array_key_exists('ui_lang', $_COOKIE))
        $params['QUERY_STRING'] .= "&lang=" . $_COOKIE['ui_lang'];

      $result = adv_exec($script, null, $params);
      if($result[0] == 0) {
        $p = strpos($result[1], "\n\n");
        if($p === false) {
          $p = strpos($result[1], "\r\n\r\n");
          if($p !== false)
            $result[1] = substr($result[1], $p + 4);
        }
        else
          $result[1] = substr($result[1], $p + 2);

        $meta = json_decode($result[1], true);
        if(array_key_exists('meta', $meta))
          $ret['meta'] = $meta['meta'];
      }
    }

    return $ret;
  }

  function save($data) {
    global $git_commit_options;
    global $current_user;
    $old_content = null;

    chdir($this->repo->path());
    adv_exec("git checkout ". shell_escape($this->repo->branch));

    if(file_exists($this->pure_id .".mapcss"))
      $old_content = file_get_contents($this->pure_id .".mapcss");

    if(strpos($this->pure_id, "/"))
      mkdir(dirname($this->pure_id), 0777, true);

    file_put_contents($this->pure_id .".mapcss", $data['content']);

    $result = array(
      "error" => 0,
      "message" => array()
    );

    $r = $this->compile(true);
    $result['error'] = $r['error'];
    $result['message'] = array_merge($result['message'], $r['message']);

    if($r['error'] != 0) {
      if($old_content === null)
        unlink($this->pure_id .".mapcss");
      else
        file_put_contents($this->pure_id .".mapcss", $old_content);

      return $result;
    }

    # read template.json translation file
    if(file_exists("translation/template.json"))
      $lang_strings = json_decode(file_get_contents("translation/template.json"), true);
    else
      $lang_strings = array();

    # create entries in translation/template.json for directory parts
    $dir_parts = explode("/", $this->pure_id);
    for($i = 1; $i < sizeof($dir_parts); $i++) {
      $lang_strings["dir:" . implode(array_slice($dir_parts, 0, $i))] = "";
    }

    # write template.json translation file
    ksort($lang_strings);
    file_put_contents("translation/template.json", json_readable_encode($lang_strings));

    adv_exec("git add ". shell_escape($this->pure_id) .".mapcss");
    adv_exec("git add translation/template.json");

    $msg = "update category {$this->id}";
    if(array_key_exists('commit_msg', $data) && $data['commit_msg'])
      $msg = $data['commit_msg'];

    $r = adv_exec("git {$git_commit_options} commit -m ". shell_escape($msg) ." --author=". shell_escape($current_user->get_author()));
    $result['error'] = $r[0];
    $result['message'] = array_merge($result['message'], array("git" => $r[1]));

    if(!in_array($result['error'], array(null, 0, 1)))
      return $result;
    else
      $result['error'] = 0;

    return $result;
  }

  function last_modified() {
    chdir($this->repo->path());

    $result = adv_exec("git log -1 --format='%at' ". shell_escape($this->repo->branch) ." ". shell_escape($this->pure_id.".mapcss"), $this->repo->path());

    return (int)$result[1];
  }

  // if necessary (or $force=true) compiles the style
  function compile($force=false) {
    global $pgmapcss;
    global $data_path;
    global $root_path;
    global $db;

    chdir($this->repo->path());
    adv_exec("git checkout ". shell_escape($this->repo->branch));

    $compiled_categories = "{$data_path}/compiled_categories";
    @mkdir($compiled_categories);
    $script = "{$compiled_categories}/{$this->script_id}.py";

    if((!$force)&&
       file_exists($script) &&
       (filemtime($script) > $this->last_modified()))
      return;

    $config_options = "-c translation_dir=" . shell_escape($this->repo->path() . "/translation") . " translation_update=true";
    if($pgmapcss['config_options'])
      $config_options .= "-c {$pgmapcss['config_options']}";

    $id = $this->id;

    $file = $this->repo->path() . "/" . $this->pure_id . ".mapcss";

    $f=adv_exec("{$pgmapcss['path']} {$config_options} --mode standalone -d'{$db['name']}' -u'{$db['user']}' -p'{$db['passwd']}' -H'{$db['host']}' -t'{$pgmapcss['template']}' --file='{$file}' --icons-parent-dir='{$root_path}/icons/' '{$this->script_id}' 2>&1", $compiled_categories, array("LC_CTYPE"=>"en_US.UTF-8"));

    return array("error"=>$f[0], "message"=>array("compile" => $f[1]));
  }
}

function ajax_mapcss_category_load($param) {
  return get_mapcss_category($param['id'])->data();
}

function ajax_mapcss_category_save($param, $post) {
  return get_mapcss_category($param['id'])->save($post);
}
