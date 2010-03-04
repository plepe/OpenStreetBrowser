<?
//include "../../render/config_queries.php";
function find_objects($param) {
  global $load_xml;
//  global $query;

  $dist_mul=(19-$param[zoom])*(19-$param[zoom]);
  $dist=5*$dist_mul;

  $ret.="<a class='zoom' href='#'>".lang("info_back")."</a><br>\n";
  $ret.=lang("search_process")."<br/>";
  $poly="PolyFromText('POLYGON((".
    ($param[lon]-$dist)." ".($param[lat]-$dist).",".
    ($param[lon]-$dist)." ".($param[lat]+$dist).",".
    ($param[lon]+$dist)." ".($param[lat]+$dist).",".
    ($param[lon]+$dist)." ".($param[lat]-$dist).",".
    ($param[lon]-$dist)." ".($param[lat]-$dist)."))', 900913)";
  $distance="Distance(way, GeometryFromText('POINT($param[lon] $param[lat])', 900913))";

  $qry="select element, (CASE WHEN id<0 THEN (select member_id from relation_members where relation_id=-id and member_role='outer' limit 1) ELSE id END), distance, way_area, instead from (".
    "select 'way' as element, 2 as r_type, osm_id as id, way, $distance-1.5*$dist_mul as distance, 1 as way_area, 
    (select 'coll_' || coll_id from coll_members left join planet_osm_colls on coll_id=id where member_id=osm_id and member_type='W' and type='street')
     as instead
      from planet_osm_line ".
    " union ".
    "select 'node' as element, 1 as r_type, osm_id as id, way, $distance-4*$dist_mul as distance, 0 as way_area, null as instead
      from planet_osm_point ".
    " union ".
    "select 'way' as element, 2 as r_type, osm_id as id, way, $distance as distance, way_area, null as instead ".
    "from planet_osm_polygon ".
    ") as t1 ".
    "where way&&$poly and distance<'$dist' order by distance";
  $res=sql_query($qry);
  $min_dist=$dist;
  $min_distg0_ind=-1;
  $min_way_area_s0=100000000000;
  $min_way_area_ind=-1;
  while($elem=pg_fetch_assoc($res)) {
    if($elem[id]) {
      $list[]=$elem;

      if($elem[distance]<$min_dist)
	$min_dist=$elem[distance];
      if(($elem[distance]>0)&&($min_distg0_ind==-1))
	$min_distg0_ind=sizeof($list)-1;
      if($elem[distance]<=0) {
	if($elem[way_area]<$min_way_area_s0) {
	  $min_way_area_s0=$elem[way_area];
	  $min_way_area_ind=sizeof($list)-1;
	}
      }
    }
  }
/*  $ret.="min_dist $min_dist<br>min_distg0_ind $min_distg0_ind<br>min_way_area_s0 $min_way_area_s0<br>min_way_area_ind $min_way_area_ind<br>\n";
  $ret.="<pre>".print_r($list, 1)."</pre>"; */

  if(!sizeof($list))
    return "<a class='zoom' href='#'>".lang("info_back")."</a><br>\n".
      lang("result_no");

  $matches=0;
  foreach($list as $elem) {
    if(($elem[distance]==$min_dist)&&($elem[way_area]==$min_way_area_s0))
      $matches++;
  }

  if($matches==1) {
    $elem=$list[$min_way_area_ind];
    if($elem[instead])
      return "redirect $elem[instead]";
    return "redirect $elem[element]_$elem[id]";
  }

  load_objects($list);

  foreach($list as $l) {
    $l1=$l;
    if($l[instead])
      $l1=$l[instead];
    if($ob=load_object($l1)) {
      $ret.=list_entry($ob->id, $ob->long_name()." - ".sprintf("%.0f", $l[distance]));
      $load_xml[]=$ob->id;
    }
  }

  return $ret;
}
