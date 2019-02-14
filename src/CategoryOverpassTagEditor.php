<?php
function ajax_CategoryOverpassTagEditorData ($param) {
  $result = array();

  $path = 'node_modules/openstreetbrowser-tag-editor-data/templates';
  $d = opendir($path);
  while ($f = readdir($d)) {
    if (preg_match("/^([^\.].*)\.json$/", $f, $m)) {
      $result[$m[1]] = json_decode(file_get_contents("{$path}/{$f}"), true);
    }
  }
  closedir($d);

  return $result;
}
