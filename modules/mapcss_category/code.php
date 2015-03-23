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
}

function ajax_mapcss_category_load($param) {
  return get_mapcss_category($param['repo'], $param['id'], $param['branch'])->data();
}
