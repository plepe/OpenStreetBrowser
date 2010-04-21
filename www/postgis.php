<?
$postgis_tables=array(
  "point"=>array(
    "id_type"=>"node",
    "sql_id_type"=>"'node'::text",
    "id_name"=>"osm_id",
    "sql_id_name"=>"osm_id",
    "full_id"=>"'node_'||osm_id",
    "geo"=>"way",
    "need_>0"=>1,
    "columns"=>array("amenity", "highway", "cuisine", "landuse", "leisure"),
    "index"=>array("amenity", "landuse", "leisure", "tourism", "heritage"),
  ),
  "polygon"=>array(
    "id_type"=>"way",
    "sql_id_type"=>"'way'::text",
    "id_name"=>"osm_id",
    "sql_id_name"=>"osm_id",
    "full_id"=>"'way_'||osm_id",
    "geo"=>"way",
    "need_>0"=>1,
    "columns"=>array("amenity", "highway", "cuisine"),
    "index"=>array("amenity", "highway"),
  ),
  "line"=>array(
    "id_type"=>"way",
    "sql_id_type"=>"'way'::text",
    "id_name"=>"osm_id",
    "sql_id_name"=>"osm_id",
    "full_id"=>"'way_'||osm_id",
    "geo"=>"way",
    "need_>0"=>1,
    "columns"=>array("amenity", "highway", "cuisine"),
    "index"=>array("amenity", "highway"),
  ),
  "rels"=>array(
    "id_type"=>"rel",
    "id_name"=>"id",
    "full_id"=>"'rel_'||osm_id",
    //"geo"=>"(select (ST_Collect((CASE WHEN p.way is not null THEN p.way WHEN po.way is not null THEN po.way WHEN l.way is not null THEN l.way END)))) from relation_members rm left join planet_osm_point p on rm.member_id=p.osm_id and rm.member_type='N' left join planet_osm_polygon po on rm.member_id=po.osm_id and rm.member_type='W' left join planet_osm_line l on rm.member_id=l.osm_id and rm.member_type='W' where rm.relation_id=planet_osm_rels.id) as center",
  ),
  "place"=>array(
    "id_type"=>"node",
    "id_name"=>"id_place_node",
    "full_id"=>"'node_'||id_place_node",
    "geo"=>"guess_area"
  ),
  "route"=>array(
    "id_type"=>"rel",
    "id_name"=>"id",
    "full_id"=>"'rel_'||id",
    "geo"=>"way"
  ),
  "stations"=>array(
    "id_type"=>array("rel", "coll", "node"),
    "id_name"=>array("rel_id", "coll_id", "stations[0]"),
    "full_id"=>array("'rel_'||rel_id", "'coll_'||coll_id", "'node_'||stations[0]"),
    "full_id"=>"'node_'||osm_id",
    "geo"=>"way",
    ),
  "streets"=>array(
    "id_type"=>"coll",
    "id_name"=>"osm_id",
    "full_id"=>"'coll_'||osm_id",
    "geo"=>"way",
  ),
);

