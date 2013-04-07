<?
Header("HTTP/1.1 404 Not Found");

if(preg_match("~^/tiles/basemap_base/~", $_SERVER['REQUEST_URI'])) {
  Header("content-type: image/png");

  print file_get_contents("plugins/basemap/404.png");
}
elseif(preg_match("~^/tiles/~", $_SERVER['REQUEST_URI'])) {
  Header("content-type: image/png");

  print file_get_contents("img/404.png");
}
else {
  print "The requested URL {$_SERVER['REQUEST_URI']} was not found on this server.";
}
