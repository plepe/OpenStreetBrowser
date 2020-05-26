<?php
$id = "openstreetbrowser";
$depend = array(
  'hooks',
  'html',
  'modulekit-lang',
  'modulekit-form',
  'modulekit-ajax',
  'openstreetbrowser-categories-main',
  'openstreetmap-tag-translations',
);
$include = array(
  'php' => array(
    'src/defaults.php',
    'src/options.php',
    'src/language.php',
    'src/ip-location.php',
    'src/wikipedia.php',
    'src/ImageLoader.php',
    'src/RepositoryBase.php',
    'src/RepositoryDir.php',
    'src/RepositoryGit.php',
    'src/repositories.php',
  ),
  'css' => array(
    'style.css',
  ),
);
$version = "4.8";
