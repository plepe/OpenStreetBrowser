<?php
$name="OpenStreetBrowser";

// an ID to identify this module
$id="openstreetbrowser";

// these modules should be loaded first
$depend=array("form", "lang");

// these files will be included in this order:
$include=array();
$include['php']=array(
  "inc/*.php",
);
$include['js']=array(
  "inc/*.js",
);
$include['css']=array(
  "inc/*.css",
);
