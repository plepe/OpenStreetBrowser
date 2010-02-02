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

$request=unserialize(file_get_contents("/osm/skunkosm/request.save"));
$cat_list=unserialize(file_get_contents("/osm/skunkosm/category_list.save"));

$importance=array("international", "national", "regional", "urban", "suburban", "local");
$types=array(
  "point"=>array(
    "id_type"=>"node",
    "id_name"=>"osm_id",
    "geo"=>"way",
    "need_>0"=>1,
  ),
  "polygon"=>array(
    "id_type"=>"way",
    "id_name"=>"osm_id",
    "geo"=>"way",
    "need_>0"=>1,
  ),
  "line"=>array(
    "id_type"=>"way",
    "id_name"=>"osm_id",
    "geo"=>"way",
    "need_>0"=>1,
  ),
  "rels"=>array(
    "id_type"=>"rel",
    "id_name"=>"id",
    //"geo"=>"(select (ST_Collect((CASE WHEN p.way is not null THEN p.way WHEN po.way is not null THEN po.way WHEN l.way is not null THEN l.way END)))) from relation_members rm left join planet_osm_point p on rm.member_id=p.osm_id and rm.member_type='N' left join planet_osm_polygon po on rm.member_id=po.osm_id and rm.member_type='W' left join planet_osm_line l on rm.member_id=l.osm_id and rm.member_type='W' where rm.relation_id=planet_osm_rels.id) as center",
  ),
  "place"=>array(
    "id_type"=>"node",
    "id_name"=>"id_place_node",
    "geo"=>"guess_area"
  ),
  "route"=>array(
    "id_type"=>"rel",
    "id_name"=>"id",
    "geo"=>"way"
  ),
  "stations"=>array(
    "id_type"=>array("rel", "coll", "node"),
    "id_name"=>array("rel_id", "coll_id", "stations[0]"),
    "geo"=>"way",
    ),
);

$ret=main();
Header("content-type: text/xml; charset=utf-8");
print $ret;

function list_print($res) {
  foreach($res as $k=>$v) {
    if($v&&preg_match("/^(.*)_id$/", $k, $m))
      $id="$m[1]_$v";
  }

  $ret="<place\n";
  $ob=load_object($id);
  $info=explode("||", $res[res]);
  $lang="en";
  global $lang_str;
  $make_valid=array("&"=>"&amp;");

  $ret.="  id=\"$id\"\n";
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

function get_list($param) {
  global $request;
  global $importance;
  global $types;

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

    if($exclude_list[node]) {
      $sql_where["point"][]=$exclude_list[node];
      $sql_where["place"][]=$exclude_list[node];
      $sql_where["stations"][]=$exclude_list[node];
    }
    if($exclude_list[way]) {
      $sql_where["polygon"][]=$exclude_list[way];
      $sql_where["line"][]=$exclude_list[way];
    }
    if($exclude_list[rel]) {
      $sql_where["rels"][]=$exclude_list[rel];
      $sql_where["route"][]=$exclude_list[rel];
      $sql_where["stations"][]=$exclude_list[rel];
    }
    if($exclude_list[coll]) {
      $sql_where["stations"][]=$exclude_list[coll];
    }
  }

  // viewbox
  if($param[viewbox]) {
    $coord=explode(",", $param[viewbox]);
    $sql_where['*'][]="way&&PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913) and Intersects(way, PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913))";
  }

  // category
  if(!($cat=$param[category]))
    return "";

//// set some more vars
  $max_count=$count+1;
  $list=array();

//// now run, until we are finished
  foreach($importance as $imp) {
    if(($max_count>0)&&($request[$cat][$imp])) {
      foreach($request[$cat][$imp] as $t=>$req_data) {
	$qry_where=array();
	if(sizeof($sql_where[$t]))
	  $qry_where[]=implode(" and ", $sql_where[$t]);
	if(sizeof($sql_where['*']))
	  $qry_where[]=implode(" and ", $sql_where['*']);
	$req_where=strtr($request[$cat][sql_where], array("%importance%"=>$imp));
	$qryc="select *, astext(ST_Centroid(way)) as center from (select ";
	if(is_array($types[$t][id_name]))
	  foreach($types[$t][id_name] as $i=>$id_name) {
	    $qryc.="{$types[$t][id_name][$i]} as {$types[$t][id_type][$i]}_id, ";
	  }
	else
	  $qryc.="{$types[$t][id_name]} as {$types[$t][id_type]}_id, ";
        $qryc.="{$types[$t][geo]} as way";
	if($req_data!=1)
	  $qryc.=", (CASE {$req_data} END) as res ";
	$qryc.=" from planet_osm_$t where $req_where) as t ";
	
	if($req_data!=1)
	  $qry_where[]="res is not null";

	if($types[$t]["need_>0"]) {
	  if(is_array($types[$t][id_name])) {
	    $q=array();
	    foreach($types[$t][id_name] as $i=>$id_name)
	      $q[]="{$types[$t][id_type][$i]}_id>0";
	    $qry_where[]="(".implode(" or ", $q).") ";
	  }
	  else
	    $qry_where[]="{$types[$t][id_type]}_id>0 ";
	}

	if(sizeof($qry_where))
	  $qryc.="where ".implode(" and ", $qry_where)." ";

	$qryc.="limit $max_count";
	//print $qryc;

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

  $ret ="<category id='$cat'";
  $ret.=" complete='".($more?"false":"true")."'";
  $ret.=">\n";
  foreach($list as $l) {
    $ret.=list_print($l);
  }
  $ret.="</category>\n";

  return $ret;
}

function main() {
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
      $r[category]=$c;
      $ret.=get_list($r);
    }
  }

  $ret.="</results>\n";

  return $ret;
}
