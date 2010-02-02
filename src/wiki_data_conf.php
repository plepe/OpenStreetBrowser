<?
require_once("conf.php");
require_once("src/wiki_stuff.php");

$columns=array(
  "Categories"=>array("category", "bg-color", "fg-color"),
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

$imp_match=array();
foreach($wiki_data["Values"] as $src) {
  $list_columns=array();
  $l=parse_wholekey($src[keys], &$list_columns);

  $r="'$src[desc]||$src[icon]";
  $r1=array();
  foreach($list_columns as $key=>$values) {
    $r1[]="$key='||\"$key\"||'";
  }
  $r.="||".implode(" ", $r1)."'";

  $prior=9;
  if(eregi("\(([0-9]+)\)", $src[overlay], $m))
    $prior=$m[1];

  if($src[importance]=="*") {
    $imp_match[$src[category]]=1;
    $src[importance]=$list_importance;
  }
  else
    $src[importance]=array($src[importance]);

  foreach($src[importance] as $imp)
    $req[$src[category]][$imp][$prior][]="WHEN $l THEN $r";

  if(!$columns[$src[category]])
    $columns[$src[category]]=array();
  $columns[$src[category]]=array_merge_recursive($columns[$src[category]], $list_columns);

}

$res=array();
foreach($req as $category=>$d1) {
  foreach($d1 as $importance=>$d2) {
    $d2_sort=array_keys($d2);
    sort($d2_sort);
    $ret="";
    foreach($d2_sort as $p) {
      $sqlstr=$d2[$p];
      $ret.=implode("\n", $sqlstr);
    }
    $res[$category][$importance]=$ret;
  }

  $cols=array_keys($columns[$category]);
  $ret1=array();
  foreach($columns[$category] as $col=>$vals) {
    $res[$category][columns][$col]=$vals;
    $ret1[]="\"$col\" in ('".implode("', '", $vals)."')";
  }
  $res[$category][sql_where]=implode(" OR ", $ret1).
    ($imp_match[$category]?" and importance='%importance%'":"");
}

function to_cat_list($cat_list, $cat_part) {
  $t=$cat_part[0];
  array_shift($cat_part);

  if(!$cat_list[$t])
    $cat_list[$t]=array();
 
  if(sizeof($cat_part))
    to_cat_list(&$cat_list[$t], $cat_part);
}

$f=fopen("request.save", "w");
fwrite($f, serialize($res));
fclose($f);

foreach($wiki_data[Categories] as $cat=>$data) {
  $cat_part=explode("/", $data[category]);
  to_cat_list(&$cat_list, $cat_part);
}

$f=fopen("category_list.save", "w");
fwrite($f, serialize($cat_list));
fclose($f);
