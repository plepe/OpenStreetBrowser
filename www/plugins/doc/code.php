<?
// Possible Paths:
// Prefix "plugin:"
//   Path for files is: www/plugins/PATH
//   The Path includes the filename without extension
//   Possible extensions:
//     .doc  ... non-translated file
//     _en.doc ... english (main) version of file
//     _XX.doc ... XX version of file (e.g. 'de')

function ajax_doc_get($param) {
  global $root_path;
  global $ui_lang;
  
  if(preg_match("/^plugin:([^\.].*)$/", $param['path'], $m))
    $path="$root_path/www/plugins/{$m[1]}";

  if(!isset($path))
    return "File not found";

  foreach(array("_{$ui_lang}", "_en", "") as $lang) {
    if(file_exists("$path$lang.doc")) {
      $final_path="$path$lang.doc";
      break;
    }
  }

  if(!isset($final_path))
    return "File not found";

  return file_get_contents($final_path);
}
