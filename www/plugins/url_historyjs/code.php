<?
function url_historyjs_html_head() {
  print "<script type='text/javascript' src='lib/history.js/scripts/bundled/html4+html5/native.history.js'></script>\n";

  $path_name=$_SERVER['SCRIPT_NAME'];
  if(preg_match("/^(.*)index.php$/", $path_name, $m))
    $path_name=$m[1];

  html_export_var(array("url_historyjs_base_url"=>"http://{$_SERVER['HTTP_HOST']}{$path_name}"));
}

register_hook("html_head", "url_historyjs_html_head");
