<?
global $renderd_file_read;

function renderd_read($p) {

  if($f=fgets($p)) {
    $f=trim($f);
    debug($f, "renderd");
  }
  else {
    debug("renderd aborted", "renderd");
    mcp_unregister_stream(MCP_READ, $p);
    pclose($p);
  }
}

function renderd_restart() {
  global $apache2_reload_cmd;
  global $root_path;

  system("killall renderd");
  gen_renderd_conf();
  chdir($root_path);
  if(!$apache2_reload_cmd)
    $apache2_reload_cmd="sudo /etc/init.d/apache2 reload";
  system($apache2_reload_cmd);

  $p=popen("software/mod_tile/renderd -f 2>&1", "r");

  mcp_register_stream(MCP_READ, $p, "renderd_read");
}

function renderd_mcp_start() {
  renderd_restart();
}

register_hook("mcp_start", "renderd_mcp_start");
register_hook("mcp_restart", "renderd_mcp_start");
register_hook("postgresql_restart_done", "renderd_restart");
