<?php
$name="OpenStreetBrowser";

// an ID to identify this module
$id="openstreetbrowser";

// these modules should be loaded first
$depend=array("hooks", "lang", "pg_sql",
  "category",		// previously from inc/category.php
  "load_object",	// previously from inc/object.php
  "pg_array",		// depend inc/object.php
  "parse_number",	// depend inc/number.php
  "tags",		// depend inc/categor*.php
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
    "inc/git_dir.php",
    "inc/git_master.php",
  ),
  'js'=>array(
    "inc/*.js",
  ),
  'css'=>array(
    "inc/*.css",
  ),
);

// The UI has been translated to following languages
$languages=array("en", "de", "it", "ja", "uk", "fr", "ru", "es", "cs", "hu", "nl", "ast", "el", "pl", "ca", "sr", "ro", "da", "pt-br", "et");
