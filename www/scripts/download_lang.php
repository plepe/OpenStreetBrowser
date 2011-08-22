#!/usr/bin/php
<?
require "../../conf.php";
require "../inc/sql.php";
require "../inc/tags.php";
require "../inc/hooks.php";
require "../inc/categories.php";

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
  "ast"=>"Asturian",
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
  $deprecated=false;
  $emptyline=0;

  $f=fopen("http://wiki.openstreetmap.org/w/index.php?title=OpenStreetBrowser/Languages/$wikipage&action=raw", "r");
  unset($file);
  while($r=fgets($f)) {
    $r=trim($r);
    if(eregi("=== Deprecated strings ===", $r)) {
      $deprecated=true;
    }
    else if(eregi("==== (File|Category): ?(.*) ====", $r, $m)) {
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

      if(isset($w)) {
	fclose($w);
	unset($w);
      }

      if($file_type==1) {
	print "Writing to $file\n";
        if($deprecated) {
          if(!($w=fopen("$root_path/$file", "a"))) {
            print "Can't write to file $file\n";
            exit;
          }
          fwrite($w, "// The following \$lang_str were not defined in the English language file and might be deprecated or wrong:\n");
        }
	else {
          if(!($w=fopen("$root_path/$file", "w"))) {
            print "Can't write to file $file\n";
            exit;
          }
        }
      }

      $emptyline=0;
    }
    elseif(eregi("<\/?syntaxhigh", $r)) {
    }
    else {
      if(eregi("^(.*)\\\$lang_str\[\"([^\"]*)\"\]\s*=\s*\"(.*)\";", $r, $m)) {
	$str=rewrite_str($m[2]);
	if($file_type==1)
	  $r="$m[1]\$lang_str[\"$str\"]=\"".strtr($m[3], array("\""=>"\\\""))."\";";
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
	  $r="$m[1]\$lang_str[\"$str\"]=array(\"".implode("\", \"", $m[3])."\");";
	else {
	  if(substr($m[1], 0, 1)!="#")
	    $lang_cat_list[$lang][$str]=$m[3];
	}
      }

      if($r=="") {
        $emptyline++;
      }
      elseif(isset($w)) {
        while($emptyline>0) {
          fwrite($w, "\n");
          $emptyline--;
        }

	fwrite($w, "$r\n");
      }

    }
  }

  if(isset($w))
    fclose($w);
}

// read all categories
$lang_cat_list=array();
foreach($languages as $lang=>$wikipage) {
  parse($lang, $wikipage);
}

$lang_new_list=array();
foreach($lang_cat_list as $lang=>$cat_list) {
  foreach($cat_list as $str=>$translation) {
    if(preg_match("/^(.+):([^:]+)$/", $str, $m)) {
      $lang_new_list[$m[1]][$m[2]][$lang]=$translation;
    }
  }
}
$lang_cat_list=$lang_new_list;

$categories=array();
$res_all=sql_query("select * from category_current", $db_central);
while($elem_all=pg_fetch_assoc($res_all)) {
  $category_id=$elem_all['category_id'];
  $version=$elem_all['version'];
  $new_version=uniqid();
  $change=false;
  $sql_str="begin;";

  $res_cat=sql_query("select * from category where category_id='$category_id' and version='$version'", $db_central);
  $elem_cat=pg_fetch_assoc($res_cat);

  $tags_cat=parse_hstore($elem_cat['tags']);
  $lang=$tags_cat['lang'];
  if(!$lang)
    $lang="en";

  $tags_old=$tags_cat;

  foreach($lang_cat_list[$category_id] as $tag=>$dummy) {
    foreach($dummy as $l=>$value) {
      if($l==$lang)
	$tags_cat["$tag"]=$value;
      else
	$tags_cat["$tag:$l"]=$value;
    }
  }

  if(sizeof(array_diff_assoc($tags_cat, $tags_old)))
    $change=true;
 
  $sql_str.="insert into category values ( '$category_id', ".array_to_hstore($tags_cat).", '$new_version', Array[ '$version' ], ''::hstore );\n";
  $sql_str.="update category_current set version='$new_version' where category_id='$category_id';\n";

  $res_rule=sql_query("select * from category_rule where category_id='$category_id' and version='$version'");
  while($elem_rule=pg_fetch_assoc($res_rule)) {
    $tags_rule=parse_hstore($elem_rule['tags']);

    $tags_old=$tags_rule;

    foreach($lang_cat_list["$category_id:{$elem_rule['rule_id']}"] as $tag=>$dummy) {
      foreach($dummy as $l=>$value) {
	if($l==$lang)
	  $tags_rule["$tag"]=$value;
	else
	  $tags_rule["$tag:$l"]=$value;
      }
    }

    if(sizeof(array_diff_assoc($tags_rule, $tags_old)))
      $change=true;
   
    $sql_str.="insert into category_rule values ( '$category_id', '{$elem_rule['rule_id']}', ".array_to_hstore($tags_rule).", '$new_version');\n";
  }

  $sql_str.="commit;\n";

  if($change) {
    print "Update category $category_id\n";
    sql_query($sql_str, $db_central);
  }
}
