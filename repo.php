<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php require 'vendor/autoload.php'; /* composer includes */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php include "node_modules/json-multiline-strings/src/json-multiline-strings.php"; ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php
if (!isset($repositories)) {
  $repositories = array(
    'default' => array(
      'path' => $config['categoriesDir'],
    ),
  );
}

if (!isset($_REQUEST['repo'])) {
  Header("Content-Type: application/json; charset=utf-8");
  print '{';

  $c = 0;
  foreach ($repositories as $repoId => $repoData) {
    print $c++ ? ',' : '';
    $d = array();
    foreach (array('name') as $k) {
      if (array_key_exists($k, $repoData)) {
        $d[$k] = $repoData[$k];
      }
    }

    print json_encode($repoId) . ':';
    print json_encode($d, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT);
  }

  print '}';
  exit(0);
}

$repo = $_REQUEST['repo'];

if (!array_key_exists($repo, $repositories)) {
  Header("HTTP/1.1 404 Repository not found");
  exit(0);
}

$path = $repositories[$repo]['path'];

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
  $cacheDir = "{$config['cache']}/repo";
  @mkdir($cacheDir);
  $cacheTs = filemtime("{$cacheDir}/{$repo}.json");
  if ($cacheTs === $ts) {
    Header("Content-Type: application/json; charset=utf-8");
    readfile("{$cacheDir}/{$repo}.json");
    exit(0);
  }
}

$data = array();

$d = opendir($path);
while ($f = readdir($d)) {
  if (preg_match("/^([0-9a-zA-Z_\-]+)\.json$/", $f, $m) && $f !== 'package.json') {
    $d1 = json_decode(file_get_contents("{$path}/{$f}"), true);
    $data[$m[1]] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'))));
  }
}
closedir($d);

$ret = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

Header("Content-Type: application/json; charset=utf-8");
print $ret;

file_put_contents("{$cacheDir}/{$repo}.json", $ret);
touch("{$cacheDir}/{$repo}.json", $ts);
