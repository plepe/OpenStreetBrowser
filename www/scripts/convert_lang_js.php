#!/usr/bin/php
<?
include "../../conf.php";

$list=array("de", "ja", "uk", "it", "fr", "ru");

foreach($list as $lang) {
  $f=fopen("$root_path/www/lang/$lang.js", "r");
  $w=fopen("$root_path/www/lang/$lang.php", "a");
  fwrite($w, "\n// The following strings were converted from JS\n");
  while($r=fgets($f)) {
    if(eregi("^(.*)lang_str\[\"([^\"]*)\"\]\s*=\s*\[(.*)\]", $r, $m)) {
      fwrite($w, "\$lang_str[\"$m[2]\"]=$m[3];\n");
    }
  }
  fclose($f);
  fclose($w);
}
