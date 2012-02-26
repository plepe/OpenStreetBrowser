<?
function osm_sql_schema_path($list) {
  $list[]=array(0, "osm");
}

register_hook("sql_schema_path", "osm_sql_schema_path");
