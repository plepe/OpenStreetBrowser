<?
function osmosis_sql_schema_path($conn) {
  global $db_osmosis;
  global $osm_schema;

  if(!isset($db_osmosis)) {
    debug("\$db_osmosis not defined in conf.php", "osmosis");
    exit;
  }

  sql_create_schema($conn, $db_osmosis['user'], "authorization {$db_osmosis['user']}");
  sql_query("grant all on all tables in schema public to {$db_osmosis['user']}");
  if(!$osm_schema)
    $osm_schema="osm_schema";
  sql_query("grant all on all tables in schema $osm_schema to {$db_osmosis['user']}");

  sql_register_schema($conn, "osmosis", $db_osmosis['user'], 0);
}

register_hook("sql_schema_path", "osmosis_sql_schema_path");
