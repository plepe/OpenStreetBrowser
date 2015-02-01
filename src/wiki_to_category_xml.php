<?
require_once("conf.php");
require_once("src/wiki_stuff.php");
//include_once "www/inc/global.php";
require_once("www/inc/sql.php");
require_once("www/inc/tags.php");
require_once("www/inc/functions.php");
require_once("www/inc/hooks.php");
require_once("www/inc/lock.php");
require_once("www/inc/git_dir.php");
require_once("www/inc/icon.php");
require_once("www/inc/data_dir.php");
function lang($x) {
  global $lang_str;
  return $lang_str[$x];
}

foreach(array("de", "it", "ja") as $lang) {
  $lang_str=array();
  require_once("www/lang/$lang.php");
  $lang_str_[$lang]=$lang_str;
}
$lang_str=array();
require_once("www/lang/en.php");

icon_init();

$columns=array(
  "Categories"=>array("category", "bg-color", "fg-color", "overlay"),
  "Values"=>array("keys", "desc", "category", "importance", "icon", "overlay", "more"),
  "Importance"=>array("key", "onlyicon", "icontext")
);

function get_author() {
  return "MCP <mcp@openstreetbrowser.org>";
}

// You have to create the user 'MCP' in the user_list table!
$current_user=new user(array("username"=>"MCP"), 1);

function wiki_download_icon($name) {
  global $icon_list;
  global $wiki_img;
  global $wiki_imgsrc;
  global $icon_dir;

  $icon=strtr($name, array(" "=>"_"));
  if(preg_match("/^(.*)\.[^\.]*$/", $name, $m))
    $icon_id=$m[1];
  else
    $icon_id=$name;

  if(preg_match("/^OSB (.*)$/i", $icon_id, $m))
    $icon_id=$m[1];

  $f=$icon_dir->get_obj($icon_id);
  if($f)
    return $icon_id;

  if(!isset($icon_list[$icon])) {
    $img_data=gzfile("$wiki_img$icon");

    if(!$img_data)
      print "Can't open $wiki_img$icon\n";

    unset($icon_path);
    foreach($img_data as $r) {
      if(eregi("<div class=\"fullImageLink\" .*<a href=\"([^\"]*)\">", $r, $m)) {
	print "DOWNLOADING $m[1]\n";
	$img=file_get_contents("$wiki_imgsrc$m[1]");
	if(!$img)
	  print "Can't download $wiki_imgsrc$m[1]\n";
      }
    }
  }

  $f=$icon_dir->create_obj($icon_id);
  $f->save("file.src", $img);

  $d=new DOMDocument();
  $tags=dom_create_append($d, "tags", $d);
  $tag=dom_create_append($tags, "tag", $d);
  $tag->setAttribute("k", "name");
  $tag->setAttribute("v", $icon_id);

  $tag=dom_create_append($tags, "tag", $d);
  $tag->setAttribute("k", "source");
  $tag->setAttribute("v", "$wiki_img$icon");

  $f->save("tags.xml", $d->saveXML());

  return $icon_id;
}

$x=$data_dir->commit_start();
if(is_array($x)) {
  print_r($x);
  exit;
}

$wiki_data=read_wiki();
$list_category=array();
$list_importance=array();
$columns=array();
$req=array();

foreach($wiki_data[Importance] as $wd) {
  $list_importance[]=$wd[key];
}

foreach($wiki_data["Categories"] as $src=>$data) {
  $list_category[$data['category']]=$data;
  $x=explode("/", $data['category']);
  for($i=1; $i<sizeof($x); $i++) {
    $n=implode("/", array_slice($x, 0, $i));
    if(!isset($list_category[$n])) {
      $list_category[$n]=array();
    }
  }
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

function parse_to_simple($keys) {
  while(preg_match("/(.*)\{\{Tag\|([^\|]*)\|([^\}\|]*)\}\}(.*)/", $keys, $m)) {
    $keys="$m[1]$m[2]=$m[3]$m[4]";
  }

  return $keys;
}

$imp_match=array();
foreach($wiki_data["Values"] as $src) {
  $rule=new tags();

  $rule->set("match", parse_to_simple($src['keys']));
  $rule->set("importance", $src['importance']);
  if($src[description])
    $rule->set("display_type", "[{$src['desc']}]");
  if(preg_match("/\[\[Image:(.*)\]\]/", $src['icon'], $m)) {
    $icon=wiki_download_icon($m[1]);
    $rule->set("icon", $icon);

    wiki_download_icon($m[1]);
  }

  $more=parse_src_more($src['more']);
  if($more[tables])
    $rule->set("type", implode(";", explode(",", $more['tables'])));

  if($x=$lang_str["tag_".strtr($rule->get("match"), array("="=>"/"))]) {
    if(is_array($x))
      $x="$x[0];$x[1]";
    $rule->set("name", $x);
  }

  foreach(array("de", "it", "ja") as $lang) {
    if($x=$lang_str_[$lang]["tag_".strtr($rule->get("match"), array("="=>"/"))]) {
      if(is_array($x))
	$x="$x[0];$x[1]";
      if($rule->get("name")!=$x)
	$rule->set("name:$lang", $x);
    }
  }

  $categories[$src[category]][]=$rule;
}

foreach($list_category as $cat_id=>$cat_data) {
  $rules=$categories[$cat_id];
  $ret="";

  $cat=new tags();

  $f=popen("grep 'cat:$cat_id' $root_path/www/lang/en.js", "r");
  $r=fgets($f);
  pclose($f);

  $cat->set("lang", "en");
  if(preg_match("/lang_str\[\".*\"\]=\[ (\".*\", )?\"(.*)\" \];/", $r, $m)) {
    $cat->set("name", $m[2]);
  }
  else {
    $cat->set("name", $cat_id);
  }

  foreach(array("de", "it", "ja") as $lang) {
    $f=popen("grep 'cat:$cat_id' $root_path/www/lang/$lang.js", "r");
    $r=fgets($f);
    pclose($f);

    if(preg_match("/lang_str\[\".*\"\]=\[ (\".*\", )?\"(.*)\" \];/", $r, $m)) {
      if($cat->get("name")!=$m[2])
	$cat->set("name:$lang", $m[2]);
    }
  }

  if(preg_match("/\//", $cat_id))
    $cat->set("hide", "yes");

  $list=array();
  foreach($list_category as $sub_cat_id=>$sub_cat_data) {
    if((substr($sub_cat_id, 0, strlen($cat_id))==$cat_id)&&
       (substr_count($sub_cat_id, "/")-substr_count($cat_id, "/")==1)) {
      $list[]=strtr($sub_cat_id, array("/"=>"_"));
    }
  }
    
  if(sizeof($list)) {
    $cat->set("sub_categories", implode(";", $list));
  }

  $ret.="<category>\n";
  $style=array();
  if($cat_data['fg-color'])
    $style[]="fill: {$cat_data['fg-color']};";
  if($cat_data['bg-color'])
    $style[]="halo_fill: {$cat_data['bg-color']};";
  if(sizeof($style))
    $cat->set("icon_text_style", implode(" ", $style));
  $ret.=$cat->write_xml("  ");
  foreach($rules as $num=>$rule) {

    $ret.="  <rule id=\"$num\">\n";
    $ret.=$rule->write_xml("    ");
    $ret.="  </rule>\n";
  }
  $ret.="</category>\n";

  $cat_id=strtr($cat_id, array("/"=>"_"));

  print "Upload $cat_id\n";
  file_put_contents("$lists_dir/$cat_id.xml", $ret);
}

$data_dir->commit_end("import vom osm wiki");
exit;

function to_cat_list(&$cat_list, $cat_part) {
  $t=$cat_part[0];
  array_shift($cat_part);

  if(!$cat_list[$t])
    $cat_list[$t]=array();
 
  if(sizeof($cat_part))
    to_cat_list($cat_list[$t], $cat_part);
}

$overlays=array();

foreach($wiki_data[Categories] as $cat=>$data) {
  $cat_part=explode("/", $data[category]);
  to_cat_list($cat_list, $cat_part);

  if($data[overlay]) {
    $overlays[$data[category]]=$data[overlay];
  }
}
