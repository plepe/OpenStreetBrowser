<?
function ajax_doc_get($param) {
  global $root_path;
  
  if(preg_match("/^plugin:([^\.].*)$/", $param['path'], $m))
    $path="$root_path/www/plugins/{$m[1]}.doc";

  if((!isset($path))||(!file_exists($path)))
    return "File not found";

  return file_get_contents($path);
}
