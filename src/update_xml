#!/usr/bin/php
<?
$root_path=$_ENV['ROOT_PATH'];
$file=$argv[1];

$full_text="";
$full_text=file_get_contents($file);

$tr=array();
$tr["__DBNAME__"]=$_ENV['DB_NAME'];
$full_text=strtr($full_text, $tr);

while(preg_match("/<InsertLayer name=\"([A-Za-z:]+)\" \/>/", $full_text, $m)) {
  $p1=strpos($full_text, $m[0]);
  $text=file_get_contents("$root_path/render/$m[1].layer");
  $full_text=substr($full_text, 0, $p1).
             $text.substr($full_text, $p1+strlen($m[0]));
}

$f=fopen("$file", "w");
fwrite($f, $full_text);
fclose($f);
