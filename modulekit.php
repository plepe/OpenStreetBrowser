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
    'src/options.php',
    'src/language.php',
    'src/ip-location.php',
    'src/wikipedia.php',
    'src/wikimedia.php',
  ),
  'css' => array(
    'style.css',
  ),
);
$version = "3.x-dev";
