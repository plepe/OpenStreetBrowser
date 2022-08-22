<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php require 'vendor/autoload.php'; /* composer includes */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("ajax_start"); /* initialize submodules */ ?>
<?php
if (!isset($db)) {
  exit(0);
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'list') {
  $result = $customCategoryRepository->list($_REQUEST);

  Header("Content-Type: application/json; charset=utf-8");
  print json_readable_encode($result);
}

if (isset($_REQUEST['id'])) {
  $category = $customCategoryRepository->getCategory($_REQUEST['id']);
  if ($category) {
    $customCategoryRepository->recordAccess($_REQUEST['id']);
  }

  Header("Content-Type: application/yaml; charset=utf-8");
  Header("Content-Disposition: inline; filename=\"{$_REQUEST['id']}.yaml\"");
  print $category;
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'save') {
  $content = file_get_contents("php://input");

  $id = $customCategoryRepository->saveCategory($content);
  $customCategoryRepository->recordAccess($id);

  Header("Content-Type: text/plain; charset=utf-8");
  print $id;
}
