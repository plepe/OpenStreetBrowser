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

foreach($wiki_data[Importance] as $wd) {
  $list_importance[]=$wd[key];
}

foreach($wiki_data["Categories"] as $src) {
  $list_category[]=$src[category];
}

print_r($list_category);
$list_columns=array();
foreach($wiki_data["Values"] as $src) {
  print_r($src[keys]);
  $l=parse_wholekey($src[keys], &$list_columns);
  print_r($l);
  print "\n";
}
