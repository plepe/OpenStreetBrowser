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

function parse_src_more($str) {
  $ret=array();

  $es=explode(" ", $str);
  foreach($es as $e) {
    if(preg_match("/^([^=]+)=(.*)$/", $e, $m))
      $ret[$m[1]]=$m[2];
  }

  return $ret;
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

  $more=parse_src_more($src[more]);
  $tables=array("point", "polygon");
  if($more[tables]) {
    $tables=explode(",", $more[tables]);
  }

  foreach($tables as $t)
    foreach($src[importance] as $imp)
      $req[$src[category]][$imp][$t][$prior][]="WHEN $l THEN $r";

  if(!$columns[$src[category]])
    $columns[$src[category]]=array();
  $columns[$src[category]]=array_merge_recursive($columns[$src[category]], $list_columns);

}

$res=array();
foreach($req as $category=>$d1) {
  foreach($d1 as $importance=>$d2) {
    foreach($d2 as $tables=>$d3) {
      $d3_sort=array_keys($d3);
      sort($d3_sort);
      $ret="";
      foreach($d3_sort as $p) {
	$sqlstr=$d3[$p];
	$ret.=implode("\n", $sqlstr);
      }
      $res[$category][$importance][$tables]=$ret;
    }
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
