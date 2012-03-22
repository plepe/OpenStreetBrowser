<?
function osmosis_sql_schema_path($conn) {
  global $osmosis_schema;

  if(!$osmosis_schema)
    $osmosis_schema="osmosis";

  sql_create_schema($conn, $conn['user']);

  sql_register_schema($conn, "osmosis", $osmosis_schema, 0);
}

register_hook("sql_schema_path", "osmosis_sql_schema_path");
