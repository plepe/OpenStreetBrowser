<?
function renderd_restart() {
  global $apache2_reload_cmd;
  global $root_path;

  system("killall renderd");
  gen_renderd_conf();
  chdir($root_path);
  if(!$apache2_reload_cmd)
    $apache2_reload_cmd="sudo /etc/init.d/apache2 reload";
  system($apache2_reload_cmd);
  system("software/mod_tile/renderd");
}

function renderd_mcp_start() {
  renderd_restart();
}

register_hook("mcp_start", "renderd_mcp_start");
register_hook("mcp_restart", "renderd_mcp_start");
register_hook("postgresql_restart_done", "renderd_restart");
