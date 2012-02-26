<?
function osb_sql_schema_path($list) {
  global $sql_replace;
  global $osb_schema;

  if(!$osb_schema)
    $osb_schema="osb_schema";

  $sql_replace['!schema:osb!']=$osb_schema;

  $list[]=array(0, $osb_schema);
}

register_hook("sql_schema_path", "osb_sql_schema_path");
