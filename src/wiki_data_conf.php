<?
require_once("conf.php");
require_once("src/wiki_stuff.php");

$columns=array(
  "Categories"=>array("category", "bg-color", "fg-color"),
  "Values"=>array("keys", "desc", "category", "network", "icon", "overlay"),
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

foreach($wiki_data["Values"] as $src) {
  $list_columns=array();
  $l=strtr(parse_wholekey($src[keys], &$list_columns), array("\""=>"\\\""));

  $r="'$src[desc]||$src[icon]'";

  $prior=9;
  if(eregi("\(([0-9]+)\)", $src[overlay], $m))
    $prior=$m[1];

  $req[$src[category]][$src[network]][$prior][]="WHEN $l THEN $r";
  foreach($list_columns as $col=>$d)
    $columns[$src[category]][$col]=1;
}

$ret ="<? // Don't change this file, generated automatically\n";
$ret.="\$request=array(\n";
foreach($req as $category=>$d1) {
  $ret.="  \"$category\"=>array(\n";
  foreach($d1 as $importance=>$d2) {
    $ret.="    \"$importance\"=>\n";
    $d2_sort=array_keys($d2);
    sort($d2_sort);
    foreach($d2_sort as $p) {
      $sqlstr=$d2[$p];
      $ret.="      \"".implode("\\n\".\n      \"", $sqlstr)."\",\n";
    }
  }

  $cols=array_keys($columns[$category]);
  $ret.="    \"columns\"=>array(\"".implode("\", \"", $cols)."\"),\n";

  $ret.="  ),\n";
}
$ret.=");\n";

print $ret;
