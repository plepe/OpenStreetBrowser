<?
function sql_query($qry, &$conn=0) {
  global $db;

  // If no database connection is supplied use default
  if(!$conn)
    $conn=&$db;

  if(!$conn) {
    print "NO DATABASE\n";
    exit;
  }

  // If database connection has not been opened yet, open it
  if(!isset($conn['connection'])) {
    $conn['connection']=
      pg_connect("dbname={$conn['name']} user={$conn['user']} password={$conn['passwd']} host={$conn['host']}");

    // Set a title for debugging
    if(!isset($conn['title']))
      $conn['title']=print_r($conn['connection'], 1);
  }

  // Do we want debug information?
  if(isset($conn['debug'])&&($conn['debug']))
    debug("CONN {$conn['title']}: ".$qry);

  // Query
  $res=pg_query($conn['connection'], $qry);

  // If we want debug information AND we have an error, tell about it
  if(isset($conn['debug'])&&($conn['debug'])&&($res===false))
    debug("CONN {$conn['title']}: ".pg_last_error());

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


