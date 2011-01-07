#!/usr/bin/php
<?
require "../../conf.php";

$languages=array(
  "en"=>"English",
  "de"=>"German",
  "fr"=>"French",
  "ja"=>"Japanese",
  "uk"=>"Ukrainian",
  "it"=>"Italian",
);

function parse($lang, $wikipage) {
  global $root_path;

  $f=fopen("http://wiki.openstreetmap.org/w/index.php?title=OpenStreetBrowser/Languages/$wikipage&action=raw", "r");
  unset($file);
  while($r=fgets($f)) {
    if(eregi("==== (File: )?(.*) ====", $r, $m)) {
      if($m[2]=="Statistics") {
	if($w)
	  fclose($w);
	continue;
      }

      $file=$m[2];
      if(eregi("^(.*)en\.(.*)$", $file, $m)) {
	$file="$m[1]$lang.$m[2]";
      }
      print "Writing to $file\n";
      if($w)
	fclose($w);
      $w=fopen("$root_path/$file", "w");
    }
    elseif(eregi("<\/?syntaxhigh", $r)) {
    }
    else {
      if($w)
	fwrite($w, $r);
    }
  }

  if($w)
    fclose($w);
}

foreach($languages as $lang=>$wikipage) {
  parse($lang, $wikipage);
}

