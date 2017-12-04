<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php require 'vendor/autoload.php'; /* composer includes */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php include "node_modules/json-multiline-strings/src/json-multiline-strings.php"; ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php
$path = $config['categoriesDir'];

$id = 'index';
if (isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
}
if (!preg_match("/^[0-9a-zA-Z_-]+$/", $id)) {
  Header("HTTP/1.1 400 Invalid ID");
  exit(0);
}

function newestTimestamp ($path) {
  $ts = 0;
  $d = opendir($path);
  while ($f = readdir($d)) {
    $t = filemtime("{$path}/{$f}");
    if ($t > $ts) {
      $ts = $t;
    }
  }
  closedir($d);

  return $ts;
}

$cacheDir = null;
$ts = newestTimestamp($path);
if (isset($config['cache'])) {
  $cacheDir = "{$config['cache']}/categories";
  @mkdir($cacheDir);
  $cacheTs = filemtime("{$config['cache']}/categories/{$id}.json");
  if ($cacheTs === $ts) {
    Header("Content-Type: application/json; charset=utf-8");
    readfile("{$config['cache']}/categories/{$id}.json");
    exit(0);
  }
}

$data = json_decode(file_get_contents("{$path}/{$id}.json"), true);
$data = jsonMultilineStringsJoin($data, array('exclude' => array(array('const'))));

function complete (&$data) {
  global $path;

  if (!array_key_exists('type', $data) && isset($data['id'])) {
    if (preg_match("/^[0-9a-zA-Z_-]+$/", $data['id'])) {
      $d = json_decode(file_get_contents("{$path}/{$data['id']}.json"), true);
      $d = jsonMultilineStringsJoin($d, array('exclude' => array(array('const'))));
      $data = array_merge($d, $data);
    }
  }

  if ($data['type'] === 'index') {
    foreach ($data['subCategories'] as $i => $cat) {
      complete($data['subCategories'][$i]);
    }
  }
}
complete($data);

$ret = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

Header("Content-Type: application/json; charset=utf-8");
print $ret;

file_put_contents("{$cacheDir}/{$id}.json", $ret);
touch("{$cacheDir}/{$id}.json", $ts);
