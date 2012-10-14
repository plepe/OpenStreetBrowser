<?
$creole_active=true;

$creole_depend=array();

$creole_tags=new tags(array(
  "name"=>"Creole Wiki Parser",
));

plugins_include_file("creole", "creole_parser.js");
