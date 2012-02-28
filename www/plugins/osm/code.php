<?
function osm_sql_schema_path($conn) {
  global $osm_schema;

  if(!$osm_schema)
    $osm_schema="osm_schema";

  sql_create_schema($conn, $osm_schema);

  sql_register_schema($conn, "osm", $osm_schema, 0);
}

register_hook("sql_schema_path", "osm_sql_schema_path");
