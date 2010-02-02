<? /*

http://..../list.php?[options]

options:
  viewbox=left,top,right,bottom
  zoom=12
  category=cat
  srs=900913
  exclude=node_123456,way_12345,...
  lang=en
  count=10

example: 
  http://.../list.php?viewbox=1820510.3841097,6140479.7509884,1821443.1547203,6139601.9194918&zoom=17&category=gastro&lang=en
*/
include "code.php";
include "inc/sql.php";
include "inc/debug.php";
include "inc/tags.php";
include "inc/object.php";
include "inc/lang.php";
require "/osm/skunkosm/x.php";

$importance=array("international", "national", "regional", "urban", "suburban", "local");
$types=array(
  "point"=>array(
    "id_type"=>"node",
    "id_name"=>"osm_id",
    "geo"=>"astext(way) as center",
  ),
  "polygon"=>array(
    "id_type"=>"way",
    "id_name"=>"osm_id",
    "geo"=>"astext(ST_Centroid(way)) as center",
  ),
  "line"=>array(
    "id_type"=>"way",
    "id_name"=>"osm_id",
    "geo"=>"astext(ST_Centroid(way)) as center",
  ),
  "rels"=>array(
    "id_type"=>"rel",
    "id_name"=>"id",
    "geo"=>"(select astext(ST_Centroid(ST_Collect((CASE WHEN p.way is not null THEN p.way WHEN po.way is not null THEN po.way WHEN l.way is not null THEN l.way END)))) from relation_members rm left join planet_osm_point p on rm.member_id=p.osm_id and rm.member_type='N' left join planet_osm_polygon po on rm.member_id=po.osm_id and rm.member_type='W' left join planet_osm_line l on rm.member_id=l.osm_id and rm.member_type='W' where rm.relation_id=planet_osm_rels.id) as center",
));

$ret=main();
Header("content-type: text/xml; charset=utf-8");
print $ret;

function list_print($res) {
  $id="$res[type]_$res[id]";
  $ret="<place\n";
  $ob=load_object($id);
  $info=explode("||", $res[res]);
  $lang="en";
  global $lang_str;
  $make_valid=array("&"=>"&amp;");

  $ret.="  id=\"$id\"\n";
  $ret.="  center=\"$res[center]\"\n";

  if($x=$ob->tags->get("name")) {
    $x=strtr($x, $make_valid);
    $ret.="  name=\"$x\"\n";
  }

  if($x=$ob->tags->get("name:$lang")) {
    $x=strtr($x, $make_valid);
    $ret.="  name:$lang=\"$x\"\n";
  }

  if($x=$ob->tags->get("$info[0]")) {
    $x=strtr($x, $make_valid);
    $ret.="  description=\"$x\"\n";
  }

  if($x=$ob->tags->get("$info[0]:$lang")) {
    $x=strtr($x, $make_valid);
    $ret.="  description:$lang=\"$x\"\n";
  }
  elseif($x=$lang_str[$ob->tags->get("$info[0]")]) {
    $x=strtr($x, $make_valid);
    $ret.="  description:$lang=\"$x\"\n";
  }

  if($x=$info[1]) {
    $x=strtr($x, $make_valid);
    $ret.="  icon=\"$info[1]\"\n";
  }

  $ret.="/>\n";

  return $ret;
}

function get_list($param) {
  global $request;
  global $importance;
  global $types;

  $count=10;
  if($param['count'])
    $count=$param['count'];

  if($param['exclude']) {
    $excl_list=explode(",", $param['exclude']);
    $exclude_list=array();
    foreach($excl_list as $e) {
      if(ereg("(node|way|rel)_([0-9]*)", $e, $m))
	$exclude_list[$m[1]][]=$m[2];
    }

    foreach($exclude_list as $type=>$excl_list) {
      $exclude_list[$type]="id not in (".implode(", ", $excl_list).")";
    }

    if($exclude_list[node])
      $sql_where["point"][]=$exclude_list[node];
    if($exclude_list[way]) {
      $sql_where["polygon"][]=$exclude_list[way];
      $sql_where["line"][]=$exclude_list[way];
    }
    if($exclude_list[rel])
      $sql_where["rels"][]=$exclude_list[rel];
  }

  $max_count=$count+1;
  $list=array();
  $search_types=array("point", "polygon");
  $cat="gastro";

  foreach($importance as $imp) {
    foreach($search_types as $t) {
      if(($max_count>0)&&($request[$cat][$imp])) {
	$where="";
	if(sizeof($sql_where[$t]))
	  $where.="and ".implode(" and ", $sql_where[$t]);
	if(sizeof($sql_where['*']))
	  $where.="and ".implode(" and ", $sql_where['*']);

	$qryc="select * from (select '{$types[$t][id_type]}' as type, {$types[$t][id_name]} as id, {$types[$t][geo]}, (CASE {$request[$cat][$imp]} END) as res from planet_osm_$t as t1) as t where res is not null and id>0 $where limit $max_count";
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
  $ret.=get_list($_REQUEST);
  $ret.="</results>\n";

  return $ret;
}
