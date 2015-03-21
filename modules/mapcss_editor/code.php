<?php
register_hook("main_links", function(&$list) {
  $list[] = array(5, "<a href='javascript:mapcss_editor_open(\"foo\")'>MapCSS</a>");
});

function ajax_mapcss_editor_load($param, $document, $data) {
  global $data_path;

  $id = $param['id'];

  if(!preg_match("/^[a-zA-Z0-9_]+$/", $id, $m))
    return array("error" => "Illegal ID");

  $ret = array();
  $ret['id'] = $id;
  $ret['content'] = file_get_contents("{$data_path}/categories/{$id}.mapcss");

  return $ret;
}

function ajax_mapcss_editor_save($param, $document, $data) {
  global $data_path;

  $data = json_decode($data, true);

  if(!array_key_exists('content', $data))
    return array("error" => "No content defined");

  $id = $param['id'];
  if(!$id)
      $id = $data['id'];

  if(!preg_match("/^[a-zA-Z0-9_]+$/", $id, $m))
    return array("error" => "Illegal ID");

  // TODO: check access rights

  @mkdir("{$data_path}/categories/", 0777, true);
  file_put_contents("{$data_path}/categories/{$id}.mapcss", $data['content']);

  $ret = array();
  call_hooks("mapcss_saved", $ret, $id);

  return $ret;
}
