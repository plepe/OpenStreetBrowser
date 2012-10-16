<?php
$name="OpenStreetBrowser";

// an ID to identify this module
$id="openstreetbrowser";

// these modules should be loaded first
$depend=array("hooks", "lang",
  "category",		// previously from inc/category.php
  "load_object",	// previously from inc/object.php
);

// include these files from modules:
$default_include=array(
  'php'=>array(
    "code.php",
  ),
  'mcp'=>array(
    "mcp.php",
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
  'mcp'=>array(
    "inc/cli.php",
    "inc/hooks.php",
    "inc/lock.php",
    "inc/number.php",
    "inc/tags.php",
    "inc/sql.php",
    "inc/debug.php",
    "inc/category.php",
    "inc/categories.php",
    "inc/process_category.php",
    "inc/functions.php",
    "inc/dom.php",
    "inc/css.php",
    "inc/postgis.php",
    "inc/git_obj.php",
    "inc/data_dir.php",
  ),
  'js'=>array(
    "inc/*.js",
  ),
  'css'=>array(
    "inc/*.css",
  ),
);
