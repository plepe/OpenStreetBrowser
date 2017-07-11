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
  ),
);
$version = "3.x-dev";
