<?php
$name="OpenStreetBrowser";

// an ID to identify this module
$id="openstreetbrowser";

// these modules should be loaded first
$depend=array("hooks");

// include these files from modules:
$default_include=array(
  'php'=>array(
    "code.php",
  ),
  'js'=>array(
    "code.js",
  ),
  'css'=>array(
    "style.css",
  ),
);

// these files will be included from base:
$include=array(
  'php'=>array(
    "inc/*.php",
  ),
  'js'=>array(
    "inc/*.js",
  ),
  'css'=>array(
    "inc/*.css",
  ),
);
