<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
/* data.php -> wrapper script for ol4pgm layers */
if(!array_key_exists('id', $_REQUEST)) {
  print "No id supplied";
  exit;
}

if(!preg_match("/^[a-zA-Z0-9_]+$/", $_REQUEST['id'], $m)) {
  print "Illegal ID";
  exit;
}

$cache_path = "{$data_path}/cache/{$_REQUEST['id']}/{$_REQUEST['z']}/{$_REQUEST['x']}/{$_REQUEST['y']}.json";

// TODO: invalidate cache
if(file_exists($cache_path)) {
  $fp = fopen($cache_path, "r");
  $cache_fp = null;
}
else {
  mkdir(dirname($cache_path), 0777, true);
  $cache_fp = fopen($cache_path, "w");

  $script = "{$data_path}/categories/{$_REQUEST['id']}.py";

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
