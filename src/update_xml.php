#!/usr/bin/php
<?
$root_path=$_ENV['ROOT_PATH'];
$file=$argv[1];

$full_text="";
$full_text=file_get_contents($file);

$tr=array();
$tr["__DBNAME__"]=$_ENV['DB_NAME'];

$full_text=strtr($full_text, $tr);

$f=fopen("$file", "w");
fwrite($f, $full_text);
fclose($f);
