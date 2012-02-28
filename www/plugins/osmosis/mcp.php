<?
function osmosis_sql_schema_path($list) {
  global $sql_replace;
  global $db_osmosis;
  global $db_central;

  if(!isset($db_osmosis))
    $db_osmosis=&$db_central;

  $sql_replace['!schema:osmosis!']=$db_osmosis['user'];

  $list[]=array(0, $db_osmosis['user']);
}

register_hook("sql_schema_path", "osmosis_sql_schema_path");

