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
    $repo = new RepositoryDir($repoId, $repoData);

    print $c++ ? ',' : '';
    print json_encode($repoId) . ':';
    print json_encode($repo->info(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT);
  }

  print '}';
  exit(0);
}

$repoId = $_REQUEST['repo'];
if (!array_key_exists($repoId, $repositories)) {
  Header("HTTP/1.1 404 Repository not found");
  exit(0);
}

$repo = new RepositoryDir($repoId, $repositories[$repoId]);

$cacheDir = null;
$ts = $repo->newestTimestamp($path);
if (isset($config['cache'])) {
  $cacheDir = "{$config['cache']}/repo";
  @mkdir($cacheDir);
  $cacheTs = filemtime("{$cacheDir}/{$repoId}.json");
  if ($cacheTs === $ts) {
    Header("Content-Type: application/json; charset=utf-8");
    readfile("{$cacheDir}/{$repoId}.json");
    exit(0);
  }
}

$data = $repo->data();

$ret = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

Header("Content-Type: application/json; charset=utf-8");
print $ret;

file_put_contents("{$cacheDir}/{$repoId}.json", $ret);
touch("{$cacheDir}/{$repoId}.json", $ts);
