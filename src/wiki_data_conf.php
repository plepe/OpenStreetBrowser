<?
require_once("conf.php");
require_once("src/wiki_stuff.php");

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
    $importance=$list_importance;
  }
  else
    $importance=array($src[importance]);

  $more=parse_src_more($src[more]);
  $tables=array("polygon", "point");
  if($more[tables]) {
    $tables=explode(",", $more[tables]);
  }

  foreach($tables as $t) {
    foreach($importance as $imp)
      if($l)
	$req[$src['category']][$imp][$t]['case'][$prior][]="WHEN $l THEN $r";
      else
	$req[$src['category']][$imp][$t]['case'][$prior][]=1;

    if($src[importance]=="*") {
      if(!$columns_all[$src[category]][$t])
	$columns_all[$src[category]][$t]=array();
      $columns_all[$src[category]][$t]=array_merge_recursive($columns_all[$src[category]][$t], $list_columns);
    }
    else {
      if(!$columns[$src[category]][$imp][$t])
	$columns[$src[category]][$imp][$t]=array();
      $columns[$src[category]][$imp][$t]=array_merge_recursive($columns[$src[category]][$imp][$t], $list_columns);
    }
  }

}

$res=array();
foreach($req as $category=>$d1) {
  foreach($d1 as $importance=>$d2) {
    foreach($d2 as $tables=>$d4) {
      $d3=$d4['case'];
      $d3_sort=array_keys($d3);
      sort($d3_sort);
      $ret="";
      foreach($d3_sort as $p) {
	$sqlstr=$d3[$p];
	$ret.=implode("\n", $sqlstr);
      }
      $res[$category][$importance][$tables]['case']=$ret;
    }
  }

  if($columns[$category]) {
    $cols=array_keys($columns[$category]);
    $ret1=array();
    foreach($columns[$category] as $importance=>$d2) {
      foreach($d2 as $tables=>$d3) {
	foreach($d3 as $col=>$vals) {
	  $res[$category][$importance][$tables]['columns'][$col]=$vals;
	  $res[$category][$importance][$tables]['where'][]="\"$col\" in ('".implode("', '", $vals)."')";
	}
      }
    }
  }

  if($columns_all[$category]) {
    $cols=array_keys($columns_all[$category]);
    $ret1=array();
    foreach($columns_all[$category] as $tables=>$d2) {
      foreach($list_importance as $importance) {
	$res[$category][$importance][$tables]['where_imp']=array();
	foreach($d2 as $col=>$vals) {
	  $res[$category][$importance][$tables]['columns'][$col]=$vals;
	  $res[$category][$importance][$tables]['where_imp'][]="\"$col\" in ('".implode("', '", $vals)."')";
	}
      }
    }
  }

  $res[$category][sql_where]=array();
  foreach($ret1 as $t=>$ret2) {
    if(sizeof($ret2))
      $res[$category][sql_where][$t][]=implode(" OR ", $ret2);
    if($imp_match[$category])
      $res[$category][sql_where][$t][]="importance='%importance%'";
    $res[$category][sql_where][$t]=implode(" and ", $res[$category][sql_where][$t]);
  }
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

$overlays=array();

foreach($wiki_data[Categories] as $cat=>$data) {
  $cat_part=explode("/", $data[category]);
  to_cat_list(&$cat_list, $cat_part);

  if($data[overlay]) {
    $overlays[$data[category]]=$data[overlay];
  }
}

$f=fopen("category_list.save", "w");
fwrite($f, serialize($cat_list));
fclose($f);

$f=fopen("overlays.save", "w");
fwrite($f, serialize($overlays));
fclose($f);
