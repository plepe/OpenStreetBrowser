<?
/*
 * You can set $renderd_cmd in conf.php to point to alternative location
 * Default: "renderd -f 2>&1"
 *
 * You should set $renderd_tiles_path in conf.php
 */
global $renderd_file_read;
global $renderd_start_time;
global $renderd_current;
$renderd_current=array();

function renderd_tiles_done($m) {
  global $renderd_tiles_path;
  global $renderd_current;

  $res=sql_query("select * from renderd_tiles where host='$renderd_tiles_path' and map='$m[1]' and zoom='$m[2]' and x_min='$m[3]' and y_min='$m[5]'");
  $elem=pg_fetch_assoc($res);
  if($elem) {
    sql_query("update renderd_tiles set previous=array_append(previous, date), date=now() where host='$renderd_tiles_path' and map='$m[1]' and zoom='$m[2]' and x_min='$m[3]' and y_min='$m[5]'");
  }
  else {
    sql_query("insert into renderd_tiles values ('$renderd_tiles_path', '$m[1]', '$m[2]', $m[3], $m[4], $m[5], $m[6], $m[7], now())");
  }

  unset($renderd_current["$m[1]-$m[2]-$m[3]-$m[4]-$m[5]-$m[6]"]);
}

function renderd_tiles_start($m) {
  global $renderd_current;

  $m[]=date("Y-m-d h:i:s");
  $renderd_current["$m[1]-$m[2]-$m[3]-$m[4]-$m[5]-$m[6]"]=$m;
}

function renderd_read() {
  global $renderd_start_time;
  global $renderd_file_read;
  global $renderd_current;

  if($f=fgets($renderd_file_read)) {
    $f=trim($f);
    debug($f, "renderd");

    if(preg_match("/DONE TILE ([A-Za-z0-9_]+) ([0-9]+) ([0-9]+)\-([0-9]+) ([0-9]+)\-([0-9]+) in ([0-9\.]+) seconds/", $f, $m)) {
      renderd_tiles_done($m);
    }
    elseif(preg_match("/START TILE ([A-Za-z0-9_]+) ([0-9]+) ([0-9]+)\-([0-9]+) ([0-9]+)\-([0-9]+)/", $f, $m)) {
      renderd_tiles_start($m);
    }
  }
  else {
    // renderd stopped, handle possible restart
    $duration=time()-$renderd_start_time;
    debug(sprintf("renderd aborted after %ds", $duration), "renderd");

    // unregister stream from select
    mcp_unregister_stream(MCP_READ, $renderd_file_read);
    pclose($renderd_file_read);
    $renderd_current=array();

    // if renderd is not working properly (quit after less than a minute) don't
    // restart but write debug message instead
    if($duration>60)
      renderd_restart();
    else {
      debug("not restarting renderd - respawning too fast", "renderd");
      $renderd_file_read=null;
    }
  }
}

function renderd_restart() {
  global $apache2_reload_cmd;
  global $root_path;
  global $renderd_start_time;
  global $renderd_cmd;
  global $renderd_file_read;
  global $renderd_current;

  system("killall renderd");
  gen_renderd_conf();
  chdir($root_path);
  if(!$apache2_reload_cmd)
    $apache2_reload_cmd="sudo /etc/init.d/apache2 reload";
  system($apache2_reload_cmd);

  $renderd_start_time=time();

  if(!$renderd_cmd)
    $renderd_cmd="renderd";

  $renderd_file_read=popen($renderd_cmd, "r");

  mcp_register_stream(MCP_READ, $renderd_file_read, "renderd_read");

  global $renderd_current;
}

function renderd_mcp_start() {
  global $renderd_tiles_path;

  if(!$renderd_tiles_path)
    $renderd_tiles_path="http://localhost/tiles";

  renderd_restart();
}

function renderd_command($str){
  global $renderd_file_read;
  global $renderd_current;

  if($str=="status") {
    print "renderd: ";
    print ($renderd_file_read?"active":"inactive");
    print "\n";

    foreach($renderd_current as $m) {
      print "  $m[1] $m[2] $m[3]-$m[4] $m[5]-$m[6] @ $m[7]\n";
    }
  }
}

register_hook("mcp_start", "renderd_mcp_start");
register_hook("mcp_restart", "renderd_mcp_start");
register_hook("mcp_command", "renderd_command");
register_hook("postgresql_restart_done", "renderd_restart");
