<?
function url_hash_http_head() {
  if($_SERVER['QUERY_STRING']!="") {
    $path=$_SERVER['SCRIPT_NAME'];
    if(preg_match("/^(.*)index.php$/", $path, $m))
      $path=$m[1];

    Header("Location: {$path}#?{$_SERVER['QUERY_STRING']}");
  }
}

register_hook("http_head", "url_hash_http_head");
