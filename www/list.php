<? /*

http://..../list.php?[options]

options:
  viewbox=left,top,right,bottom
  zoom=12
  category=cat
  srs=900913
  exclude=node_123456,way_12345,...
  lang=en

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

  $ret.="  id=\"$id\"\n";
  $ret.="  center=\"$res[center]\"\n";

  if($x=$ob->tags->get("name"))
    $ret.="  name=\"$x\"\n";

  if($x=$ob->tags->get("name:$lang"))
    $ret.="  name:$lang=\"$x\"\n";

  if($x=$ob->tags->get("$info[0]"))
    $ret.="  description=\"$x\"\n";

  if($x=$ob->tags->get("$info[0]:$lang"))
    $ret.="  description:$lang=\"$x\"\n";
  elseif($x=$lang_str[$ob->tags->get("$info[0]")])
    $ret.="  description:$lang=\"$x\"\n";

  if($info[1]) {
    $ret.="  icon=\"$info[1]\"\n";
  }

  $ret.="/>\n";

  return $ret;
}

function get_list() {
  global $request;
  $max_count=10+1;
  $list=array();
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
  $search_types=array("point", "polygon");
  $cat="gastro";

  foreach($importance as $imp) {
    foreach($search_types as $t) {
      if(($max_count>0)&&($request[$cat][$imp])) {
	$qryc="select * from (select '{$types[$t][id_type]}' as type, {$types[$t][id_name]} as id, {$types[$t][geo]}, (CASE {$request[$cat][$imp]} END) as res from planet_osm_$t as t1) as t where res is not null and id>0 limit $max_count";
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

  $ret="";
  foreach($list as $l) {
    $ret.=list_print($l);
  }

  return $ret;
}

function main() {
  $ret ="<?xml version='1.0' encoding='UTF-8'?>\n";
  $ret.="<results generator='OpenStreetBrowser'>\n";
  $ret.=get_list();
  $ret.="</results>\n";

  return $ret;
}
