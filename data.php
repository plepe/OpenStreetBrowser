<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
/* data.php -> wrapper script for ol4pgm layers */
if(!array_key_exists('category', $_REQUEST)) {
  print "No category id supplied";
  exit;
}

if(!preg_match("/^[a-z\/A-Z0-9_\@]+$/", $_REQUEST['category'], $m)) {
  print "Illegal category ID";
  exit;
}

$category_id = $_REQUEST['category'];

$category = get_mapcss_category($category_id);

$mapcss = $category->repo->path() . "/{$category_id}.mapcss";

if(!array_key_exists('x', $_REQUEST) &&
   !array_key_exists('y', $_REQUEST) &&
   !array_key_exists('z', $_REQUEST)) {
  $read_from_cache = false;
}
else {
  $cache_path = $category->cache_path($_SERVER);

  $read_from_cache = true;
}

if($read_from_cache) {
  if(!file_exists($cache_path))
    $read_from_cache = false;
  elseif(filemtime($cache_path) < $category->last_modified())
    $read_from_cache = false;
  elseif(filemtime($cache_path) < time() - 86400*10)
    $read_from_cache = false;
}

if(!$read_from_cache) {
  sql_query("insert into data_request values (now(), " . postgre_escape($category_id) . ", " . postgre_escape(serialize($_SERVER)) . ", " . postgre_escape($cache_path) . ")");

  if(isset($cache_path)) {
    mkdir(dirname($cache_path), 0777, true);
  }

  Header("HTTP/1.1 200 Not rendered yet");
  print "Not rendered yet\n";
  exit(0);
}
else {

  $fp = gzopen($cache_path, "r");

  // first read and set headers
  while($r = trim(fgets($fp))) {
    Header($r);
  }

  // now print the body
  while($r = fread($fp, 1024*1024)) {
    print $r;
  }

  fclose($fp);

}

// TODO: maybe record error somewhere?
// TODO: first fill cache then print, so that http error codes can be used?
// TODO: blacklist script?
// if an error occured, remove the cache file
if($error) {
  unlink($cache_path);
  exit(1);
}
