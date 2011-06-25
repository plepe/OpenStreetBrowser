#!/usr/bin/php
<?
require "../../conf.php";
require "../inc/sql.php";
require "../inc/tags.php";

$languages=array(
  "en"=>"English",
  "de"=>"German",
  "fr"=>"French",
  "ja"=>"Japanese",
  "uk"=>"Ukrainian",
  "it"=>"Italian",
  "ru"=>"Russian",
  "es"=>"Spanish",
  "cs"=>"Czech",
  "hu"=>"Hungarian",
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
  global $lang_cat_list;

  $f=fopen("http://wiki.openstreetmap.org/w/index.php?title=OpenStreetBrowser/Languages/$wikipage&action=raw", "r");
  unset($file);
  while($r=fgets($f)) {
    if(eregi("==== (File|Category): ?(.*) ====", $r, $m)) {
      if($m[2]=="Statistics") {
	if($w) {
	  print "Done\n";
	  fclose($w);
	  unset($w);
	}
	continue;
      }

      if($m[1]=="File") {
	$file_type=1;
	$file=$m[2];
	if(eregi("^(.*)en\.(.*)$", $file, $m)) {
	  $file="$m[1]$lang.$m[2]";
	}
      }
      else {
	$file_type=2;
	$file=$m[2];
      }

      if($w) {
	fclose($w);
	unset($w);
      }

      if($file_type==1) {
	print "Writing to $file\n";
	if(!($w=fopen("$root_path/$file", "w"))) {
	  print "Can't write to file $file\n";
	  exit;
	}
      }
    }
    elseif(eregi("<\/?syntaxhigh", $r)) {
    }
    else {
      if(eregi("^(.*)\\\$lang_str\[\"([^\"]*)\"\]\s*=\s*\"(.*)\";", $r, $m)) {
	$str=rewrite_str($m[2]);
	if($file_type==1)
	  $r="$m[1]\$lang_str[\"$str\"]=\"".strtr($m[3], array("\""=>"\\\""))."\";\n";
	else {
	  if(substr($m[1], 0, 1)!="#")
	    $lang_cat_list[$lang][$str]=$m[3];
	}
      }

      elseif(eregi("^(.*)\\\$lang_str\[\"([^\"]*)\"\]\s*=\s*array\( *\"(.*)\" *\);", $r, $m)) {
	$m[3]=explode("\", \"", $m[3]);
	foreach($m[3] as $mk=>$mv) {
	  $m[3][$mk]=strtr($mv, array("\""=>"\\\""));
	}

	$str=rewrite_str($m[2]);
	if($file_type==1)
	  $r="$m[1]\$lang_str[\"$str\"]=array(\"".implode("\", \"", $m[3])."\");\n";
	else {
	  if(substr($m[1], 0, 1)!="#")
	    $lang_cat_list[$lang][$str]=$m[3];
	}
      }

      if($w)
	fwrite($w, $r);
    }
  }

  if($w)
    fclose($w);
}

// read all categories
$lang_cat_list=array();
$categories=array();
$res_all=sql_query("select * from category_current", $db_central);
while($elem_all=pg_fetch_assoc($res_all)) {
  $categories[$elem_all['category_id']]=array("version"=>$elem_all['version']);
  $res_cat=sql_query("select * from category_rule where category_id='{$elem_all['category_id']}' and version='{$elem_all['version']}'", $db_central);
  while($elem_cat=pg_fetch_assoc($res_cat)) {
    $categories[$elem_all['category_id']]['rule'][$elem_cat['rule_id']]=array();
  }
}

foreach($languages as $lang=>$wikipage) {
  parse($lang, $wikipage);
}
