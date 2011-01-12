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
  "ru"=>"Russian",
);

function rewrite_str($str) {
  if($str=="head_action")
    return "head:actions";
  if(eregi("^head_(.*)$", $str, $m))
    return "head:$m[1]";
  if($str=="read_more")
    return "wikipedia:read_more";
  if(eregi("^map_key_(.*)$", $str, $m))
    return "map_key:$m[1]";
  if(eregi("^yes/(.*)$", $str, $m))
    return "$m[1]";
  if(eregi("^highway_type_(.*)$", $str, $m))
    return "tag:highway=$m[1]";
  if(eregi("^admin_level=(.*)$", $str, $m))
    return "tag:admin_level=$m[1]";
  if(eregi("^tag\/(.*)$", $str, $m))
    return "tag:$m[1]";
  if(eregi("^tag_(.*)\/(.*)$", $str, $m))
    return "tag:$m[1]=$m[2]";

  return $str;
}

function parse($lang, $wikipage) {
  global $root_path;

  $f=fopen("http://wiki.openstreetmap.org/w/index.php?title=OpenStreetBrowser/Languages/$wikipage&action=raw", "r");
  unset($file);
  while($r=fgets($f)) {
    if(eregi("==== (File: )?(.*) ====", $r, $m)) {
      if($m[2]=="Statistics") {
	if($w) {
	  print "Done\n";
	  fclose($w);
	  unset($w);
	}
	continue;
      }

      $file=$m[2];
      if(eregi("^(.*)en\.(.*)$", $file, $m)) {
	$file="$m[1]$lang.$m[2]";
      }

      if($w) {
	fclose($w);
	unset($w);
      }

      print "Writing to $file\n";
      if(!($w=fopen("$root_path/$file", "w"))) {
	print "Can't write to file $file\n";
	exit;
      }
    }
    elseif(eregi("<\/?syntaxhigh", $r)) {
    }
    else {
      if(eregi("^(.*)\\\$lang_str\[\"([^\"]*)\"\](.*)$", $r, $m)) {
	$str=rewrite_str($m[2]);
        $r="$m[1]\$lang_str[\"$str\"]$m[3]";
      }

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
