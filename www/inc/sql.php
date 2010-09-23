<?
function sql_query($qry) {
  global $sql_debug;

  if($sql_debug)
    debug($qry);

  $res=pg_query($qry);

  if(($sql_debug)&&($res===false))
    debug(pg_last_error());

  return $res;
}

function postgre_escape($str) {
  return "E'".strtr($str, array("'"=>"\\'", "\\"=>"\\\\"))."'";
}

function array_to_hstore($array) {
  $replace=array("'"=>"\\'", "\""=>"\\\\\"", "\\"=>"\\\\\\\\");
  $ret=array();
  foreach($array as $k=>$v) {
    $ret[]="\"".strtr($k, $replace)."\"=>\"".strtr($v, $replace)."\"";
  }

  return "E'".implode(", ", $ret)."'::hstore";
}

function parse_hstore($text) {
  return eval("return array($text);");
}


