<?
function ajax_doc_get($param) {
  global $root_path;
  $path="$root_path/www/plugins/{$param['plugin']}/doc.txt";

  if(!file_exists($path))
    return "File not found";

  return file_get_contents($path);
}
