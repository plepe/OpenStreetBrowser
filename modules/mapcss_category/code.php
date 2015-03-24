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
  }

  function data() {
    $ret = array(
      'id' => $this->id,
      'repo' => $this->repo->id,
      'branch' => $this->repo->branch,
    );

    chdir($this->repo->path());

    $ret['content'] = "";
    $f = popen("git show ". shell_escape($this->repo->branch) .":". shell_escape($this->id .".mapcss"), "r");
    while($r = fread($f, 1024))
      $ret['content'] .= $r;
    pclose($f);

    return $ret;
  }

  function save($data) {
    global $git_commit_options;
    global $current_user;

    chdir($this->repo->path());
    system("git checkout ". shell_escape($this->repo->branch));

    file_put_contents($this->id .".mapcss", $data['content']);

    system("git add ". shell_escape($this->id) .".mapcss");

    $msg = "update category {$this->id}";
    if(array_key_exists('commit_msg', $data) && $data['commit_msg'])
      $msg = $data['commit_msg'];

    $result = adv_exec("git {$git_commit_options} commit -m ". shell_escape($msg) ." --author=". shell_escape($current_user->get_author()));

    return $result;
  }

  // if necessary (or $force=true) compiles the style
  function compile($force=false) {
    global $pgmapcss;
    global $db;

    chdir($this->repo->path());
    system("git checkout ". shell_escape($this->repo->branch));

    if((!$force)&&
       file_exists($this->id .".py") &&
       (filemtime($this->id .".py") > filemtime($this->id .".mapcss")))
      return;

    $config_options = "";
    if($pgmapcss['config_options'])
      $config_options = "-c {$pgmapcss['config_options']}";

    $id = $this->id;

    $f=adv_exec("{$pgmapcss['path']} {$config_options} --mode standalone -d'{$db['name']}' -u'{$db['user']}' -p'{$db['passwd']}' -H'{$db['host']}' -t'{$pgmapcss['template']}' '{$id}' 2>&1", $this->repo->path(), array("LC_CTYPE"=>"en_US.UTF-8"));

    return $f[1];
  }
}

function ajax_mapcss_category_load($param) {
  return get_mapcss_category($param['repo'], $param['id'], $param['branch'])->data();
}

function ajax_mapcss_category_save($param, $post) {
  return get_mapcss_category($param['repo'], $param['id'], $param['branch'])->save($post);
}
