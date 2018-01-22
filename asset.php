<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php require 'vendor/autoload.php'; /* composer includes */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php
$allRepositories = getRepositories();

$repoId = $_REQUEST['repo'];
if (!array_key_exists($repoId, $allRepositories)) {
  Header("HTTP/1.1 404 Repository not found");
  exit(0);
}

$repoData = $allRepositories[$repoId];
$repo = getRepo($repoId, $repoData);

$tmpfile = tempnam('/tmp', 'osb-asset-');
file_put_contents($tmpfile, $repo->file_get_contents($_REQUEST['file']));
$mime_type = mime_content_type($tmpfile);

Header("Content-Type: {$mime_type}; charset=utf-8");
readfile($tmpfile);
