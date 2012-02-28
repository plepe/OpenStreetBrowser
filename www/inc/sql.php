<?
global $db;
global $sql_replace;
if(!isset($sql_replace))
  $sql_replace=array();

function sql_create_schema(&$conn, $schema, $param="") {
  $res=pg_query($conn['connection'], "SELECT nspname AS name FROM pg_namespace WHERE nspname !~ '^pg_.*' and nspname='{$schema}'");
  if(pg_num_rows($res)==0) {
    pg_query($conn['connection'], "create schema $schema $param");
  }

  return true;
}

function sql_register_schema(&$conn, $alias, $schema, $weight=0) {
  global $db;
  global $db_central;

  // set schema search path
  $conn['schema_path'][]=array($weight, $schema);
  $path=implode(", ", weight_sort($conn['schema_path']));
  $conn['replace']['!schema:search_path!']=$path;

  // set search paths to database
  if(isset($conn['connection']))
    pg_query($conn['connection'], "set search_path to $path");

  // register replacement string
  $conn['replace']["!schema:$alias!"]=$schema;
}

function sql_get_schema_path(&$conn) {
  // initialize
  $conn['schema_path']=array();
  $conn['replace']=array();
  sql_register_schema($conn, 'this', $conn['user'], -10);
  sql_register_schema($conn, 'public', "public", 10);

  // requestr list of schemata
  call_hooks("sql_schema_path", &$conn);

  pg_query($conn['connection'], "set search_path to {$conn['replace']['!schema:search_path!']}");
}

function sql_connect(&$conn) {
  // If database connection has not been opened yet, open it
  if(!isset($conn['connection'])) {
    // connect
    $conn['connection']=
      pg_connect("dbname={$conn['name']} user={$conn['user']} password={$conn['passwd']} host={$conn['host']}");

    // check for successful connection
    if(!$conn['connection']) {
      debug("db connection failed", "sql");

      call_hooks("sql_connection_failed", &$conn);

      // still no valid connection - exit
      if(!$conn['connection']) {
        print "db connection failed\n";
        exit;
      }
    }

    // Set a title for debugging
    if(!isset($conn['title']))
      $conn['title']=print_r($conn['connection'], 1);

    // save time of connection start
    $conn['date']=time();

    // set schema search path
    sql_get_schema_path(&$conn);

    // inform other modules about successful database connection
    if($conn['connection'])
      call_hooks("sql_connect", $conn);
    
    // log opening of connection
    debug("db connection {$conn['connection']} opened", "sql");
  }
}

// Issue a sql query.
// $qry     ... the string of the query
// $conn    ... the database connection to use (optional)
// $replace ... an array for strtr for strings to be replaced (optional)
//
// by default the strings in the global $sql_replace variable will be replaced.
// Plugins may add strings there, e.g.:
// !schema:osb!   	a specific schema; will be defined by plugins
function sql_query($qry, &$conn=0, $replace=array()) {
  global $db;
  global $sql_replace;

  // If no database connection is supplied use default
  if(!$conn)
    $conn=&$db;

  if(!$conn) {
    print "NO DATABASE\n";
    exit;
  }

  // check if connection was opened too long
  if(isset($conn['date'])&&(time()-$conn['date']>3600)) {
    debug("connection {$conn['connection']} opened too long, closing", "sql");
    $conn['date']=null;
    pg_close($conn['connection']);
    unset($conn['connection']);
  }

  // check for database connection
  sql_connect(&$conn);

  // replace strings in query
  $replace=array_merge($conn['replace'], $replace);
  $replace=array_merge($sql_replace, $replace);
  $qry=strtr($qry, $replace);

  // Do we want debug information?
  if(isset($conn['debug'])&&($conn['debug']))
    debug("CONN {$conn['title']}: ".$qry, "sql");

  // Query
  $res=pg_query($conn['connection'], $qry);

  // There was an error - call hooks to inform about error
  if($res===false) {
    // if postgresql connection died ...
    if(pg_connection_status($conn['connection'])==PGSQL_CONNECTION_BAD) {
      debug("sql connection died", "sql");
      pg_close($conn['connection']);
      unset($conn['connection']);

      call_hooks("sql_connection_failed", &$conn);

      // if connection is back, retry query
      if(isset($conn['connection'])&&
         (pg_connection_status($conn['connection'])==PGSQL_CONNECTION_OK)) {
        $res=pg_query($conn['connection'], $qry);

        if($res!==false) {
          debug("sql retry successful", "sql");
          return $res;
        }
      }
      else {
        print "sql connection died\n";
        exit;
      }
    }

    $error=pg_last_error();
    call_hooks("sql_error", &$db, $qry, $error);

    // If we want debug information AND we have an error, tell about it
    if(isset($conn['debug'])&&($conn['debug']))
      debug("CONN {$conn['title']}: ".pg_last_error(), "sql");
  }

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

