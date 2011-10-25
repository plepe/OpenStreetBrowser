<?
function sql_connect(&$conn) {
  // If database connection has not been opened yet, open it
  if(!isset($conn['connection'])) {
    // connect
    $conn['connection']=
      pg_connect("dbname={$conn['name']} user={$conn['user']} password={$conn['passwd']} host={$conn['host']}");

    // Set a title for debugging
    if(!isset($conn['title']))
      $conn['title']=print_r($conn['connection'], 1);

    // save time of connection start
    $conn['date']=time();

    // inform other modules about successful database connection
    if($conn['connection'])
      call_hooks("sql_connect", $conn);
  }
}

function sql_query($qry, &$conn=0) {
  global $db;

  // If no database connection is supplied use default
  if(!$conn)
    $conn=&$db;

  if(!$conn) {
    print "NO DATABASE\n";
    exit;
  }

  // check if connection was opened too long
  if(isset($conn['date'])&&(time()-$conn['date']>3600)) {
    $conn['date']=null;
    pg_close($conn['connection']);
    unset($conn['connection']);
  }

  // check for database connection
  sql_connect(&$conn);

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


