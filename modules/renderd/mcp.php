<?
/*
 * You can set $renderd_cmd in conf.php to point to alternative location
 * Default: "renderd"
 *
 * You should set $renderd_tiles_path in conf.php
 *
 * Hooks:
 * renderd_get_maps(list)
 * -> add entries to the list:
 *    "id"=>array("file"=>"/path/to/file.mapnik")
 */
global $renderd_proc;
global $renderd_pipes;
global $renderd_start_time;
global $renderd_current;
$renderd_current=array();

function renderd_print_entry($id, $entry) {
  $ret ="[{$id}]\n";
  $ret.="URI=/tiles/{$id}/\n";
  $ret.="XML={$entry['file']}\n";
  $ret.="HOST=dummy.host\n";
  $ret.="\n";

  return $ret;
}

function renderd_gen_conf() {
  global $root_path;
  global $lists_dir;

  $conf=fopen("$root_path/data/renderd.conf", "w");

  if(file_exists("$root_path/data/renderd.conf.template"))
    $template=file_get_contents("$root_path/data/renderd.conf.template");
  else
    $template=file_get_contents("$root_path/src/renderd.conf.template");
  fwrite($conf, $template);

  if(file_exists("$root_path/data/renderd.conf.local")) {
    $template=file_get_contents("$root_path/data/renderd.conf.local");
    fwrite($conf, $template);
  }

  // TODO: maybe move to "renderd_categories" or so?
  foreach(category_list() as $f=>$tags) {
    $conf_part=file_get_contents("$lists_dir/$f.renderd");
    fwrite($conf, $conf_part);
  }

  global $renderd_files;
  if($renderd_files) foreach($renderd_files as $file) {
    if(file_exists($file)) {
      fwrite($conf, file_get_contents($file));
    }
  }
  
  $renderd=array();
  call_hooks("renderd_get_maps", &$renderd);

  foreach($renderd as $id=>$entry) {
    fwrite($conf, renderd_print_entry($id, $entry));
  }

  // generate dummy entry in renderd.conf to avoid renderd-bug
  global $data_path;
  fwrite($conf, "[dummy]\n");
  fwrite($conf, "URI=/tiles/dummy/\n");
  fwrite($conf, "XML=/home/osm/data/render_dummy.xml\n");
  fwrite($conf, "HOST=dummy.host\n");

  fclose($conf);
}

function renderd_tiles_done($m) {
  global $renderd_tiles_path;
  global $renderd_current;
  global $db_central;

  $res=sql_query("select * from renderd_tiles where host='$renderd_tiles_path' and map='$m[1]' and zoom='$m[2]' and x_min='$m[3]' and y_min='$m[5]'");
  $elem=pg_fetch_assoc($res);
  if($elem) {
    sql_query("update renderd_tiles set previous=array_append(previous, date), date=now() where host='$renderd_tiles_path' and map='$m[1]' and zoom='$m[2]' and x_min='$m[3]' and y_min='$m[5]'", $db_central);
  }
  else {
    sql_query("insert into renderd_tiles values ('$renderd_tiles_path', '$m[1]', '$m[2]', $m[3], $m[4], $m[5], $m[6], $m[7], now())", $db_central);
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
  global $renderd_pipes;
  global $renderd_current;

  if($f=fgets($renderd_pipes[1])) {
    $f=trim($f);
    $debug_level=D_DEBUG;

    if(preg_match("/DONE TILE ([A-Za-z0-9_]+) ([0-9]+) ([0-9]+)\-([0-9]+) ([0-9]+)\-([0-9]+) in ([0-9\.]+) seconds/", $f, $m)) {
      renderd_tiles_done($m);
      $debug_level=D_NOTICE;
    }
    elseif(preg_match("/START TILE ([A-Za-z0-9_]+) ([0-9]+) ([0-9]+)\-([0-9]+) ([0-9]+)\-([0-9]+)/", $f, $m)) {
      renderd_tiles_start($m);
      $debug_level=D_NOTICE;
    }

    debug($f, "renderd", $debug_level);
  }
  else {
    // renderd stopped, handle possible restart
    $duration=time()-$renderd_start_time;
    debug(sprintf("renderd aborted after %ds", $duration), "renderd");

    // unregister stream from select
    mcp_unregister_stream(MCP_READ, $renderd_pipes[1]);
    fclose($renderd_pipes[0]);
    fclose($renderd_pipes[1]);
    fclose($renderd_pipes[2]);
    $renderd_current=array();

    // if renderd is not working properly (quit after less than a minute) don't
    // restart but write debug message instead
    if($duration>60)
      renderd_restart();
    else {
      debug("not restarting renderd - respawning too fast", "renderd");
      unset($renderd_pipes);
      unset($renderd_proc);
    }
  }
}

function get_proc_children($pid, $tree=null) {
  $tree=array();
  $ret=array();

  // build process tree in $tree
  if($tree==null) {
    $p=popen("ps eo pid,ppid", "r");
    while($r=fgets($p)) {
      if(preg_match("/^\s*([0-9]+)\s+([0-9]+)/", $r, $m)) {
	if(!isset($tree[$m[2]]))
	  $tree[$m[2]]=array();

	$tree[$m[2]][]=$m[1];
      }
    }
  }

  // no children? return
  if(!isset($tree[$pid]))
    return array();

  // add grand children
  foreach($tree[$pid] as $child) {
    $ret=array_merge($ret, get_proc_children($child, &$tree));
  }

  return array_merge($ret, $tree[$pid]);
}

function renderd_stop() {
  global $renderd_proc;

  if(!isset($renderd_proc))
    return;

  debug("Stopping renderd. Sending term signal.", "renderd");
  $status=proc_get_status($renderd_proc);

  // find all children of our started command
  $plist=get_proc_children($status['pid']);

  // kill all children
  foreach($plist as $pid)
    posix_kill($pid, 15);

  // now kill the children itself
  proc_terminate($renderd_proc);

  sleep(1);
  $status=proc_get_status($renderd_proc);
  while($status['running']) {
    $status=proc_get_status($renderd_proc);
  }
  unset($renderd_proc);

  debug("renderd exited.", "renderd");
}

function renderd_restart() {
  global $apache2_reload_cmd;
  global $root_path;
  global $renderd_start_time;
  global $renderd_cmd;
  global $renderd_proc;
  global $renderd_pipes;
  global $renderd_current;

  renderd_gen_conf();
  renderd_stop();

  chdir($root_path);
  if(!$apache2_reload_cmd)
    $apache2_reload_cmd="sudo /etc/init.d/apache2 reload";
  system($apache2_reload_cmd);

  $renderd_start_time=time();

  if(!$renderd_cmd)
    $renderd_cmd="renderd";

  $descriptors=array(
    0=>array("pipe", "r"),
    1=>array("pipe", "w"),
    2=>array("pipe", "w"),
  );
  $renderd_pipes=array();
  $renderd_proc=
    proc_open("$renderd_cmd -f 2>&1", $descriptors, $renderd_pipes);

  mcp_register_stream(MCP_READ, $renderd_pipes[1], "renderd_read");

  global $renderd_current;
}

function renderd_mcp_start() {
  global $renderd_tiles_path;

  if(!$renderd_tiles_path)
    $renderd_tiles_path="http://localhost/tiles";

  renderd_restart();
}

function renderd_command($str){
  global $renderd_pipes;
  global $renderd_current;

  if($str=="status") {
    print "renderd: ";
    print ($renderd_pipes[1]?"active":"inactive");
    print "\n";

    foreach($renderd_current as $m) {
      print "  $m[1] $m[2] $m[3]-$m[4] $m[5]-$m[6] @ $m[7]\n";
    }
  }

  if($str=="renderd stop") {
    renderd_stop();
  }
  if($str=="renderd start") {
    renderd_restart();
  }
}

function renderd_mcp_stop() {
  renderd_stop();
}

register_hook("mcp_start", "renderd_mcp_start");
register_hook("mcp_restart", "renderd_mcp_start");
register_hook("mcp_stop", "renderd_mcp_stop");
register_hook("mcp_command", "renderd_command");
register_hook("postgresql_restart_done", "renderd_restart");
