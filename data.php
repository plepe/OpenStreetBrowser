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
$category->compile();

$mapcss = $category->repo->path() . "/{$category_id}.mapcss";

if(!array_key_exists('x', $_REQUEST) &&
   !array_key_exists('y', $_REQUEST) &&
   !array_key_exists('z', $_REQUEST)) {
  $read_from_cache = false;
}
else {
  $esc_category_id = id_escape($category_id);
  $cache_path = "{$data_path}/cache/{$esc_category_id}/{$_REQUEST['z']}/{$_REQUEST['x']}/{$_REQUEST['y']}.json.gz";

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
// TODO: invalidate cache
  $semaphore = sem_get(1, $pgmapcss['max_parallel']);
  sem_acquire($semaphore);

  if(isset($cache_path)) {
    mkdir(dirname($cache_path), 0777, true);
  }

  $error = $category->execute($_SERVER, $cache_path);
}

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

if(isset($semaphore))
  sem_release($semaphore);

// TODO: maybe record error somewhere?
// TODO: first fill cache then print, so that http error codes can be used?
// TODO: blacklist script?
// if an error occured, remove the cache file
if($error) {
  unlink($cache_path);
  exit(1);
}
