<? /*

http://..../list.php?[options]

options:
  viewbox=left,top,right,bottom
  zoom=12
  category=culture/religion,gastro
  srs=900913
  exclude=node_123456,way_12345,...
  lang=en
  count=10

example: 
  http://.../list.php?viewbox=1820510.3841097,6140479.7509884,1821443.1547203,6139601.9194918&zoom=17&category=gastro&lang=en
*/
$design_hidden=1;
include "code.php";
include "inc/sql.php";
include "inc/debug.php";
include "inc/tags.php";
include "inc/object.php";
include "inc/lang.php";
include "postgis.php";

$importance=array("international", "national", "regional", "urban", "suburban", "local");

$ret=main();
Header("content-type: text/xml; charset=utf-8");
print $ret;

function list_print($res) {
  $id=$res[id];
  print_r($res);

  $ret="<match\n";
  $ob=load_object($id);
  $info=explode("||", $res[res]);
  $lang="en";
  global $lang_str;
  $make_valid=array("&"=>"&amp;", "\""=>"&quot;", "<"=>"&lt;", ">"=>"&gt;");

  $ret.="  id=\"$id\"\n";
  $ret.="  rule_id=\"$res[rule_id]\"\n";
  $ret.="  center=\"$res[center]\"\n";

  if($x=$ob->long_name()) {
    $x=strtr($x, $make_valid);
    $ret.="  name=\"$x\"\n";
  }

  if($x=$ob->long_name($lang)) {
    $x=strtr($x, $make_valid);
    $ret.="  name_trans=\"$x\"\n";
  }

  if($x=$ob->tags->get("$info[0]")) {
    $x=strtr($x, $make_valid);
    $ret.="  description=\"$x\"\n";
  }

  if($x=$ob->tags->get("$info[0]:$lang")) {
    $x=strtr($x, $make_valid);
    $ret.="  description_trans=\"$x\"\n";
  }
  elseif($x=$lang_str["$info[0]=".$ob->tags->get("$info[0]")]) {
    $x=strtr($x, $make_valid);
    $ret.="  description_trans=\"$x\"\n";
  }

  if($x=$info[1]) {
    $x=strtr($x, $make_valid);
    $ret.="  icon=\"$x\"\n";
  }

  if($x=$info[2]) {
    $x=strtr($x, $make_valid);
    $ret.="  data=\"$x\"\n";
  }

  $ret.="/>\n";

  return $ret;
}

function get_list($category, $param) {
  global $request;
  global $importance;
  global $postgis_tables;
  global $lists_dir;

  // load category configuration
  if(!file_exists("$lists_dir/$category.xml.save"))
    return null;

  $list_data=unserialize(file_get_contents("$lists_dir/$category.xml.save"));

//// process params ////
  // count
  $count=10;
  if($param['count'])
    $count=$param['count'];

  // exclude
  if($param['exclude']) {
    $excl_list=explode(",", $param['exclude']);
    $exclude_list=array();
    foreach($excl_list as $e) {
      if(ereg("(node|way|rel|coll)_([0-9]*)", $e, $m))
	$exclude_list[$m[1]][]=$m[2];
    }

    foreach($exclude_list as $type=>$excl_list) {
      $exclude_list[$type]="{$type}_id not in (".implode(", ", $excl_list).")";
    }

    foreach($postgis_tables as $type=>$type_conf) {
      if(is_array($type_conf[id_type])) {
	foreach($type_conf[id_type] as $id_type)
	  if($exclude_list[$id_type])
	    $sql_where[$type][]=$exclude_list[$id_type];
      }
      else {
	if($exclude_list[$type_conf[id_type]])
	  $sql_where[$type][]=$exclude_list[$type_conf[id_type]];
      }
    }
  }

  // viewbox
  if($param[viewbox]) {
    $coord=explode(",", $param[viewbox]);
    $sql_where['*'][]="geo&&PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913) and Intersects(SnapToGrid(geo, 0.00001), PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913))";
  }

//// set some more vars
  $max_count=$count+1;
  $list=array();

//// now run, until we are finished
  foreach($importance as $imp) {
    if(($max_count>0)&&($list_data[$imp])) {
      foreach($list_data[$imp] as $t=>$req_data) {
	$qry_where=array();
	if(sizeof($sql_where[$t]))
	  $qry_where[]=implode(" and ", $sql_where[$t]);
	if(sizeof($sql_where['*']))
	  $qry_where[]=implode(" and ", $sql_where['*']);

	$req_where=array();
	if($req_data[where])
	  $req_where[]=implode(" or ", $req_data[where]);

	if(is_array($req_data[where_imp])) {
	  if(sizeof($req_data[where_imp]))
	    $req_where[]="(".implode(" or ", $req_data[where_imp]).") and \"importance\"='$imp'";
	  else
	    $req_where[]="\"importance\"='$imp'";
	}

	if(sizeof($req_where))
	  $req_where="where ".implode(" or ", $req_where);
	else
	  $req_where="";

        $where=implode(" and ", $qry_where);

	$qryc="select *, astext(ST_Centroid(geo)) as center from (";
	$qryc.=$req_data[sql];
	$qryc.=") as x where $where limit $max_count";
	//print "==$qryc==";
	
	$resc=sql_query($qryc);
	$max_count-=pg_num_rows($resc);
	while($elemc=pg_fetch_assoc($resc))
	  $list[]=$elemc;
      }
    }
  }

//  if($max_count>0) {
//    $qryc="select * from (select 'rel' as type, id, (CASE {$request[gastro][suburban]} END) as res from planet_osm_point as t1) as t where res is not null limit $max_count";
//    $resc=sql_query($qryc);
//    $max_count-=pg_num_rows($resc);
//    while($elemc=pg_fetch_assoc($resc))
//      $list[]=$elemc;
//  }

  $more=0;
  if(sizeof($list)>$count) {
    $list=array_slice($list, 0, $count);
    $more=1;
  }

  $ret ="<category id='$category'";
  $ret.=" complete='".($more?"false":"true")."'";
  $ret.=">\n";
  foreach($list as $l) {
    $ret.=list_print($l);
  }
  $ret.="</category>\n";

  return $ret;
}

function main() {
  global $lists_dir;
  $ret ="<?xml version='1.0' encoding='UTF-8'?>\n";
  $ret.="<results generator='OpenStreetBrowser'>\n";

  $ret.="<request";
  foreach($_REQUEST as $rk=>$rv) {
    $ret.=" $rk=\"".htmlentities(stripslashes($rv))."\"";
  }
  $ret.="/>\n";

  $r=$_REQUEST;
  if($r[category]) {
    $cs=explode(",", $r[category]);
    foreach($cs as $c) {
      unset($load_cat);
      // This is a custom list

      $ret.=get_list($c, $r);
    }
  }

  $ret.="</results>\n";

  return $ret;
}
