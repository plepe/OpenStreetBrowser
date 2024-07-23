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
    'src/database.php',
    'src/options.php',
    'src/language.php',
    'src/ip-location.php',
    'src/wikidata.php',
    'src/wikipedia.php',
    'src/ImageLoader.php',
    'src/RepositoryBase.php',
    'src/RepositoryDir.php',
    'src/RepositoryGit.php',
    'src/repositories.php',
    'src/repositoriesGitea.php',
    'src/customCategory.php',
  ),
  'css' => array(
    'style.css',
  ),
);
$version = "5.4";
