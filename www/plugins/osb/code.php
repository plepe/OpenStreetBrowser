<?
function osb_sql_schema_path($conn) {
  global $osb_schema;

  if(!$osb_schema)
    $osb_schema="osb_schema";

  sql_create_schema($conn, $osb_schema);

  sql_register_schema($conn, "osb", $osb_schema, 0);
}

register_hook("sql_schema_path", "osb_sql_schema_path");
