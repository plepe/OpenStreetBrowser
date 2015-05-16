<?php
global $mapcss_category_cache;

function get_mapcss_category($repo, $id, $branch="master") {
  if(!array_key_exists($repo, $mapcss_category_cache))
    $mapcss_category_cache[$repo] = array();

  if(!array_key_exists($id, $mapcss_category_cache[$repo]))
    $mapcss_category_cache[$repo][$id] = array();

  if(!array_key_exists($branch, $mapcss_category_cache[$repo][$id]))
    $mapcss_category_cache[$repo][$id][$branch] = new mapcss_Category($repo, $id, $branch);

  return $mapcss_category_cache[$repo][$id][$branch];
}

class mapcss_Category {
  function __construct($repo, $id, $branch="master") {
    $this->repo = get_category_repository($repo, $branch);
    $this->id = $id;

    $this->full_id = id_escape($repo) . "_" . id_escape($id) . "_" . id_escape($branch);
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
    $f = popen("git show ". shell_escape($this->repo->branch) .":". shell_escape($this->id .".mapcss"), "r");
    while($r = fread($f, 1024))
      $ret['content'] .= $r;
    pclose($f);

    global $data_path;
    $compiled_categories = "{$data_path}/compiled_categories";
    $script = "{$compiled_categories}/{$this->full_id}.py";

    if(!file_exists($script))
      $this->compile();

    if(file_exists($script)) {
      $params = $_SERVER;

      if(array_key_exists('QUERY_STRING', $params))
        $params['QUERY_STRING'] .= "&meta=only";
      else
        $params['QUERY_STRING'] = "meta=only";

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

    if(file_exists($this->id .".mapcss"))
      $old_content = file_get_contents($this->id .".mapcss");

    file_put_contents($this->id .".mapcss", $data['content']);

    $result = array(
      "error" => 0,
      "message" => array()
    );

    $r = $this->compile(true);
    $result['error'] = $r['error'];
    $result['message'] = array_merge($result['message'], $r['message']);

    if($r['error'] != 0) {
      if($old_content === null)
        unlink($this->id .".mapcss");
      else
        file_put_contents($this->id .".mapcss", $old_content);

      return $result;
    }

    adv_exec("git add ". shell_escape($this->id) .".mapcss");

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

    $result = adv_exec("git log -1 --format='%at' ". shell_escape($this->repo->branch) ." ". shell_escape($this->id.".mapcss"), $this->repo->path());

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
    $script = "{$compiled_categories}/{$this->full_id}.py";

    if((!$force)&&
       file_exists($script) &&
       (filemtime($script) > $this->last_modified()))
      return;

    $config_options = "";
    if($pgmapcss['config_options'])
      $config_options = "-c {$pgmapcss['config_options']}";

    $id = $this->id;

    $file = $this->repo->path() . "/" . $this->id . ".mapcss";

    $f=adv_exec("{$pgmapcss['path']} {$config_options} --mode standalone -d'{$db['name']}' -u'{$db['user']}' -p'{$db['passwd']}' -H'{$db['host']}' -t'{$pgmapcss['template']}' --file='{$file}' --icons-parent-dir='{$root_path}/icons/' '{$this->full_id}' 2>&1", $compiled_categories, array("LC_CTYPE"=>"en_US.UTF-8"));

    return array("error"=>$f[0], "message"=>array("compile" => $f[1]));
  }
}

function ajax_mapcss_category_load($param) {
  return get_mapcss_category($param['repo'], $param['id'], $param['branch'])->data();
}

function ajax_mapcss_category_save($param, $post) {
  return get_mapcss_category($param['repo'], $param['id'], $param['branch'])->save($post);
}
