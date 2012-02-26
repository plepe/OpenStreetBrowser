<?
function osm_sql_schema_path($list) {
  global $sql_replace;
  global $osm_schema;

  if(!$osm_schema)
    $osm_schema="osm_schema";

  $sql_replace['!schema:osm!']=$osm_schema;

  $list[]=array(0, $osm_schema);
}

register_hook("sql_schema_path", "osm_sql_schema_path");
