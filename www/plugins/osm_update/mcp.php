<?
global $osm_update_last_start;
$osm_update_last_start=0;

function osm_update_read_state() {
  global $root_path;
  $working_dir="$root_path/data/updates";

  $f=fopen("$working_dir/state.txt", "r");
  $state=array();
  while($r=fgets($f)) {
    $r=explode("=", stripslashes(trim($r)));
    if(sizeof($r)==2)
      $state[$r[0]]=$r[1];
  }
  fclose($f);

  return $state;
}

function osm_update_status() {
  $ret="";

  $state=osm_update_read_state();

  if(!isset($state['timestamp'])) {
    global $osm_update_proc;
    $osm_update_proc=1;
    return "update not correctly configured";
  }

  $state_timestamp=new DateTime($state['timestamp']);
  $ret.="Timestamp: ".$state_timestamp->format("Y-m-d H:i:s");

  $ret.=", ";

  $state_now=new DateTime("now");
  $d=$state_timestamp->diff($state_now);
  if($d->days>0)
    $ret.=$d->format("Back: %ad, %h:%I");
  else
    $ret.=$d->format("Back: %h:%I");

  return $ret;
}

function osm_update_read_stdout($p) {
  global $osm_update_proc;
  global $osm_update_last_start;

  if($ret=chop(fgets($p))) {
    debug("$p", "osm_update");
  }
  else {
    mcp_unregister_stream(MCP_READ, $p);
    fclose($p);
    $exit=proc_close($osm_update_proc);

    if($exit!=0) {
      debug("osmosis stopped with error code {$exit}", "osm_update");
    }

    $osm_update_proc=null;
    $osm_update_last_start=time();
  }
}

function osm_update_read_stderr($p) {
  if($error=chop(fgets($p))) {
    debug("$error", "osm_update");
  }
  elseif($p) {
    mcp_unregister_stream(MCP_READ, $p);
  }
}

function osm_update_start() {
  global $db_central;
  global $root_path;
  global $osm_update_proc;
  global $osm_update_last_start;
  global $db_osmosis;
  $working_dir="$root_path/data/updates";

  // make sure the start of two update commands are at least 30 seconds apart
  if($osm_update_last_start+30>time())
    return;

  // it's disabled
  if($osm_update_proc==1)
    return;

  debug(osm_update_status(), "osm_update");

  // it's disabled
  if($osm_update_proc==1)
    return;

  if(!file_exists("$working_dir/state.txt")) {
    debug("ERROR: state_file does not exist. Please create the working directory data/updates/. See http://wiki.openstreetmap.org/wiki/Minutely_Mapnik for details.", "osm_update");

    // disable
    $osm_update_proc=1;
    return;
  }

  $descriptors=array(
    0=>array("pipe", "r"),
    1=>array("pipe", "w"),
    2=>array("pipe", "w"));

  $command="osmosis --read-replication-interval workingDirectory=$working_dir --simplify-change --write-pgsimp-change host={$db_osmosis['host']} user={$db_osmosis['user']} password={$db_osmosis['passwd']} database={$db_osmosis['name']}";

  debug("osm_update", "starting osmosis ".Date("r"));
  $osm_update_proc=proc_open($command, $descriptors, $pipes, null, array("JAVACMD_OPTIONS"=>"-Xmx512M"));

  mcp_register_stream(MCP_READ, $pipes[1], "osm_update_read_stdout");
  mcp_register_stream(MCP_READ, $pipes[2], "osm_update_read_stderr");
//  if($stdin)
//    fwrite($pipes[0], $stdin);

  fclose($pipes[0]);
}

function osm_update_tick() {
  global $osm_update_proc;

  if(!$osm_update_proc) {
    osm_update_start();
  }
}

function osm_update_command($str) {
  global $osm_update_proc;

  if($str=="status") {
    print "osm_update: ";
    if($osm_update_proc==1)
      print "disabled\n";
    elseif($osm_update_proc)
      print "active";
    else
      print "resting";

    print "\n  ".osm_update_status()."\n";
  }
}

function osm_update_import_done() {
  global $osmosis_path;
  global $root_path;
  global $db_osmosis;
  debug("Loading osmosis actions-table", "osm_update", D_NOTICE);

  // remember search_path and set to 'osmosis'
  $res=sql_query("show search_path", $db_osmosis);
  $elem=pg_fetch_array($res);
  $search_path=$elem[0];
  sql_query("set search_path to !schema:osmosis!, !schema:this!, public", $db_osmosis);

  // load file
  sql_query(file_get_contents("$osmosis_path/script/pgsimple_schema_0.6_action.sql"), $db_osmosis);
  sql_query(file_get_contents("$root_path/www/plugins/osm_update/functions.sql"), $db_osmosis);

  // reset search_path
  sql_query("set search_path to {$search_path}", $db_osmosis);

}

register_hook("mcp_command", "osm_update_command");
register_hook("mcp_tick", "osm_update_tick");
register_hook("osm_import_done", "osm_update_import_done");

// THIS IS MISSING
//  if($last_clean+7200<time()) {
//    print "More than an hour passed since last cache clean ...\n";
//    sql_query("select cache_clean()");
//    $last_clean=time();
//  }
