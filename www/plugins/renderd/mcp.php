<?
global $renderd_file_read;
global $renderd_start_time;

function renderd_read($p) {
  global $renderd_start_time;

  if($f=fgets($p)) {
    $f=trim($f);
    debug($f, "renderd");
  }
  else {
    // renderd stopped, handle possible restart
    $duration=time()-$renderd_start_time;
    debug(sprintf("renderd aborted after %ds", $duration), "renderd");

    // unregister stream from select
    mcp_unregister_stream(MCP_READ, $p);
    pclose($p);

    // if renderd is not working properly (quit after less than a minute) don't
    // restart but write debug message instead
    if($duration>60)
      renderd_restart();
    else
      debug("not restarting renderd - respawning too fast", "renderd");
  }
}

function renderd_restart() {
  global $apache2_reload_cmd;
  global $root_path;
  global $renderd_start_time;

  system("killall renderd");
  gen_renderd_conf();
  chdir($root_path);
  if(!$apache2_reload_cmd)
    $apache2_reload_cmd="sudo /etc/init.d/apache2 reload";
  system($apache2_reload_cmd);

  $renderd_start_time=time();
  $p=popen("software/mod_tile/renderd -f 2>&1", "r");

  mcp_register_stream(MCP_READ, $p, "renderd_read");
}

function renderd_mcp_start() {
  renderd_restart();
}

register_hook("mcp_start", "renderd_mcp_start");
register_hook("mcp_restart", "renderd_mcp_start");
register_hook("postgresql_restart_done", "renderd_restart");
