<?
function osb_sql_schema_path($list) {
  $list[]=array(0, "osb");
}

register_hook("sql_schema_path", "osb_sql_schema_path");
