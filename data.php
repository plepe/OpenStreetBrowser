<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
/* data.php -> wrapper script for ol4pgm layers */
if(!array_key_exists('category', $_REQUEST)) {
  print "No category id supplied";
  exit;
}

if(!preg_match("/^[a-zA-Z0-9_]+$/", $_REQUEST['category'], $m)) {
  print "Illegal category ID";
  exit;
}

$repo = $_REQUEST['repo'];
$category_id = $_REQUEST['category'];
$branch = $_REQUEST['branch'] ?: "master";

$category = get_mapcss_category($repo, $category_id, $branch);
$category->compile();

$script = $category->repo->path() . "/{$category_id}.py";
$mapcss = $category->repo->path() . "/{$category_id}.mapcss";

if(!array_key_exists('x', $_REQUEST) &&
   !array_key_exists('y', $_REQUEST) &&
   !array_key_exists('z', $_REQUEST)) {
  $read_from_cache = false;
}
else {
  $cache_path = "{$data_path}/cache/{$repo}/{$category_id}/{$branch}/{$_REQUEST['z']}/{$_REQUEST['x']}/{$_REQUEST['y']}.json.gz";

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

// TODO: invalidate cache
$cache_fp = null;
if($read_from_cache) {
  $fp = gzopen($cache_path, "r");
}
else {
  if(isset($cache_path)) {
    mkdir(dirname($cache_path), 0777, true);
    $cache_fp = gzopen($cache_path, "w");
  }

  $descriptorspec = array(
     0 => array("pipe", "r"),
     1 => array("pipe", "w"),
     2 => array("pipe", "w")
  );

  // execute external script as CGI
  $process = proc_open($script, $descriptorspec, $pipes, getcwd(), $_SERVER);
  $fp = $pipes[1];
}

// first read and set headers
while($r = trim(fgets($fp))) {
  Header($r);

  if($cache_fp)
    fwrite($cache_fp, "$r\n");
}

if($cache_fp)
  fwrite($cache_fp, "\n");

// now print the body
while($r = fread($fp, 1024*1024)) {
  print $r;

  if($cache_fp)
    fwrite($cache_fp, $r);
}

if(isset($process))
  proc_close($process);
if($cache_fp)
  fclose($cache_fp);
