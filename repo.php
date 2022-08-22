<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php require 'vendor/autoload.php'; /* composer includes */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php include "node_modules/json-multiline-strings/src/json-multiline-strings.php"; ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php
$allRepositories = getRepositories();

if (!isset($_REQUEST['repo'])) {
  Header("Content-Type: application/json; charset=utf-8");
  print '{';

  $c = 0;
  foreach ($allRepositories as $repoId => $repoData) {
    $repo = getRepo($repoId, $repoData);

    if (!$repo->isEmpty()) {
      print $c++ ? ',' : '';
      print json_encode($repoId, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . ':';
      $info = $repo->info();

      if (isset($repoData['repositoryUrl'])) {
	$info['repositoryUrl'] = $repoData['repositoryUrl'];
      }
      if (isset($repoData['categoryUrl'])) {
	$info['categoryUrl'] = $repoData['categoryUrl'];
      }
      $info['group'] = $repoData['group'] ?? 'default';

      print json_encode($info, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT);
    }
  }

  print '}';
  exit(0);
}

$fullRepoId = $_REQUEST['repo'];
list($repoId, $branchId) = explode('~', $fullRepoId);
if (array_key_exists('lang', $_REQUEST)) {
  $fullRepoId .= '~' . $_REQUEST['lang'];
}

if (!array_key_exists($repoId, $allRepositories)) {
  Header("HTTP/1.1 404 Repository not found");
  exit(0);
}

$repoData = $allRepositories[$repoId];
$repo = getRepo($repoId, $repoData);

if ($branchId) {
  try {
    $repo->setBranch($branchId);
  }
  catch (Exception $e) {
    Header("HTTP/1.1 404 No such branch");
    exit(0);
  }
}

if (array_key_exists('file', $_REQUEST)) {
  $file = $repo->file_get_contents($_REQUEST['file']);

  if ($file === false) {
    Header("HTTP/1.1 403 Forbidden");
    print "Access denied.";
  }
  else if ($file === null) {
    Header("HTTP/1.1 404 File not found");
    print "File not found.";
  }
  else {
    Header("Content-Type: text/plain; charset=utf-8");
    print $file;
  }

  exit(0);
}

$cacheDir = null;
$ts = $repo->timestamp($path);
if (isset($config['cache'])) {
  $cacheDir = "{$config['cache']}/repo";
  @mkdir($cacheDir);
  $cacheTs = filemtime("{$cacheDir}/{$fullRepoId}.json");
  if ($cacheTs === $ts) {
    Header("Content-Type: application/json; charset=utf-8");
    readfile("{$cacheDir}/{$fullRepoId}.json");
    exit(0);
  }
}

$data = $repo->data($_REQUEST);

$repo->updateLang($data, $_REQUEST);

if (!array_key_exists('index', $data['categories'])) {
  $data['categories']['index'] = array(
    'type' => 'index',
    'subCategories' => array_map(
      function ($k) {
        return array('id' => $k);
      }, array_keys($data['categories']))
  );
}

if (isset($repoData['repositoryUrl'])) {
  $data['repositoryUrl'] = $repoData['repositoryUrl'];
}
if (isset($repoData['categoryUrl'])) {
  $data['categoryUrl'] = $repoData['categoryUrl'];
}

$ret = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

Header("Content-Type: application/json; charset=utf-8");
print $ret;

if ($cacheDir) {
  @mkdir(dirname("{$cacheDir}/{$fullRepoId}"));
  file_put_contents("{$cacheDir}/{$fullRepoId}.json", $ret);
  touch("{$cacheDir}/{$fullRepoId}.json", $ts);
}
