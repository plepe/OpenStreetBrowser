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

$script = "{$data_path}/categories/{$_REQUEST['id']}.py";

$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

// execute external script as CGI
$process = proc_open($script, $descriptorspec, $pipes, getcwd(), $_SERVER);

// first read and set headers
while($r = chop(fgets($pipes[1]))) {
  if($r == "")
    break;

  Header($r);
}

// now print the body
while($r = fread($pipes[1], 1024*1024)) {
  print $r;
}

proc_close($process);
