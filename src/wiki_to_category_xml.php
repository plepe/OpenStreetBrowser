<?
require_once("conf.php");
require_once("src/wiki_stuff.php");
require_once("www/inc/tags.php");
require_once("www/inc/functions.php");

$columns=array(
  "Categories"=>array("category", "bg-color", "fg-color", "overlay"),
  "Values"=>array("keys", "desc", "category", "importance", "icon", "overlay", "more"),
  "Importance"=>array("key", "onlyicon", "icontext")
);

$wiki_data=read_wiki();
$list_category=array();
$list_importance=array();
$columns=array();
$req=array();

foreach($wiki_data[Importance] as $wd) {
  $list_importance[]=$wd[key];
}

foreach($wiki_data["Categories"] as $src) {
  $list_category[]=$src[category];
}

function parse_src_more($str) {
  $ret=array();

  $es=explode(" ", $str);
  foreach($es as $e) {
    if(preg_match("/^([^=]+)=(.*)$/", $e, $m))
      $ret[$m[1]]=$m[2];
  }

  return $ret;
}

$categories=array();

$imp_match=array();
foreach($wiki_data["Values"] as $src) {
  $rule=new tags();

  $rule->set("match", $src[keys]);
  $rule->set("importance", $src[importance]);
  if($src[description])
    $rule->set("display_type", "[$src[desc]]");
  if(preg_match("/\[\[Image:(.*)\]\]/", $src[icon], $m))
    $rule->set("icon", "osm_wiki:$m[1]");

  $more=parse_src_more($src[more]);
  if($more[tables])
    $rule->set("type", implode(";", explode(",", $more[tables])));

  $categories[$src[category]][]=$rule;
}

foreach($categories as $cat_id=>$rules) {
  $ret="";

  $cat=new tags();

  $f=popen("grep 'cat:$cat_id' /osm/skunkosm/www/lang/en.js", "r");
  $r=fgets($f);
  pclose($f);

  if(preg_match("/lang_str\[\".*\"\]=\[ (\".*\", )?\"(.*)\" \];/", $r, $m)) {
    $cat->set("name", $m[2]);
    $cat->set("lang", "en");
  }
  else {
    $cat->set("name", $cat_id);
  }

  $f=popen("grep 'cat:$cat_id' /osm/skunkosm/www/lang/de.js", "r");
  $r=fgets($f);
  pclose($f);

  if(preg_match("/lang_str\[\".*\"\]=\[ (\".*\", )?\"(.*)\" \];/", $r, $m)) {
    $cat->set("name:de", $m[2]);
  }

  $ret.="<category>\n";
  $ret.=$cat->write_xml("  ");
  foreach($rules as $num=>$rule) {
    $ret.="  <rule id=\"$num\">\n";
    $ret.=$rule->write_xml("    ");
    $ret.="  </rule>\n";
  }
  $ret.="</category>\n";

  $f=do_post_request("http://www.openstreetbrowser.org/skunk/categories.php?todo=save&id=new", $ret);
}
exit;

function to_cat_list($cat_list, $cat_part) {
  $t=$cat_part[0];
  array_shift($cat_part);

  if(!$cat_list[$t])
    $cat_list[$t]=array();
 
  if(sizeof($cat_part))
    to_cat_list(&$cat_list[$t], $cat_part);
}

$overlays=array();

foreach($wiki_data[Categories] as $cat=>$data) {
  $cat_part=explode("/", $data[category]);
  to_cat_list(&$cat_list, $cat_part);

  if($data[overlay]) {
    $overlays[$data[category]]=$data[overlay];
  }
}
