<?
function sql_query($qry) {
  global $sql_debug;

  if($sql_debug)
    debug($qry);

  return pg_query($qry);
}
