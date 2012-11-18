<?
//include "../../render/config_queries.php";
function whats_here_find($param) {
  global $load_xml;
//  global $query;
  $srid=900913;
  if(isset($param['srid']))
    $srid=$param['srid'];

  $dist_mul=(19-$param[zoom])*(19-$param[zoom]);
  $dist=3*$dist_mul;

  $poly="ST_Transform(PolyFromText('POLYGON((".
    ($param[lon]-$dist)." ".($param[lat]-$dist).",".
    ($param[lon]-$dist)." ".($param[lat]+$dist).",".
    ($param[lon]+$dist)." ".($param[lat]+$dist).",".
    ($param[lon]+$dist)." ".($param[lat]-$dist).",".
    ($param[lon]-$dist)." ".($param[lat]-$dist)."))', $srid), 4326)";
  $distance="ST_Distance(way, ST_Transform(GeometryFromText('POINT($param[lon] $param[lat])', $srid), 4326))";

  $qry="select *, astext(ST_Centroid(way)) as \"#geo:center\" from (".
    "select *, $distance-\"#dist_modi\"*$dist_mul as \"#distance\" from (".
    "select id, tags, way, 1 as \"#area\", 4 as \"#dist_modi\" from osm_point($poly)".
    " union all ".
    "select id, tags, way, ST_Length(way) as \"#area\", 1.5 as \"#dist_modi\" from osm_line($poly)".
    " union all ".
    "select id, tags, way, ST_Area(way) as \"#area\", 1 as \"#dist_modi\" from osm_polygon($poly)".
    " union all ".
    "select id, tags, way, ST_Area(way) as \"#area\", 1 as \"#dist_modi\" from osm_rel($poly)".
    ") x1 offset 0) x2 where \"#distance\"<$dist order by \"#distance\" asc, \"#area\" asc";

  $res=sql_query($qry);
  while($elem=pg_fetch_assoc($res)) {
    $osm_tags=parse_hstore($elem['tags']);
    foreach($elem as $k=>$v) {
      if(substr($k, 0, 1)=="#")
        $osm_tags[$k]=$v;
    }

    $ret[]=array("id"=>$elem['id'], "tags"=>new tags($osm_tags));
  }

  return $ret;
}

function ajax_whats_here_find($param, $xml) {
  $ret=whats_here_find($param);

  $result=$xml->createElement("result");
  $list=dom_create_append($result, "list", $xml);

  foreach($ret as $ob) {
    $match=dom_create_append($list, "match", $xml);
    foreach($ob as $k=>$v) {
      if($k=="tags") {
        $v=$v->export_dom($xml);
        foreach($v as $v1)
          $match->appendChild($v1);
      }
      else
        $match->setAttribute($k, $v);
    }
  }

  $xml->appendChild($result);
}



