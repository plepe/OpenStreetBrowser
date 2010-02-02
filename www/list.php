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

  if(1) {
    $qryc="select * from (select 'node' as type, osm_id as id, (CASE {$request[gastro][suburban]} END) as res from planet_osm_point as t1) as t where res is not null and id>0 limit $max_count";
    $resc=sql_query($qryc);
    $max_count-=pg_num_rows($resc);
    while($elemc=pg_fetch_assoc($resc))
      $list[]=$elemc;
  }

  if($max_count>0) {
    $qryc="select * from (select 'way' as type, osm_id as id, (CASE {$request[gastro][suburban]} END) as res from planet_osm_polygon as t1) as t where res is not null and id>0 limit $max_count";
    $resc=sql_query($qryc);
    $max_count-=pg_num_rows($resc);
    while($elemc=pg_fetch_assoc($resc))
      $list[]=$elemc;
  }

  if($max_count>0) {
    $qryc="select * from (select 'way' as type, osm_id as id, (CASE {$request[gastro][suburban]} END) as res from planet_osm_line as t1) as t where res is not null and id>0 limit $max_count";
    $resc=sql_query($qryc);
    $max_count-=pg_num_rows($resc);
    while($elemc=pg_fetch_assoc($resc))
      $list[]=$elemc;
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
