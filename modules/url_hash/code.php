<?
function url_hash_http_head() {
  $path=$_SERVER['SCRIPT_NAME'];
  if(preg_match("/^(.*)index.php$/", $path, $m))
    $path=$m[1];

  $query=substr($_SERVER['REQUEST_URI'], strlen($path));

  if(strlen($query))
    Header("Location: {$path}#{$query}");
}

register_hook("http_head", "url_hash_http_head");
