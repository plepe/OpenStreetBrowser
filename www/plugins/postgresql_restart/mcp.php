<?
function postgresql_restart_do($conn) {
  global $postgresql_restart_cmd;

  debug("Restarting PostgreSQL server", "postgresql_restart");

  // if not localhost connection, ignore
  if($conn['host']!="localhost")
    return;

  if(!$postgresql_restart_cmd)
    $postgresql_restart_cmd="sudo /etc/init.d/postgresql restart";

  // restart via sudo
  system($postgresql_restart_cmd);

  // try to connect (again)
  $conn['connection']=
    pg_connect("dbname={$conn['name']} user={$conn['user']} password={$conn['passwd']} host={$conn['host']}");

  // if successful inform other modules
  if(pg_connection_status($conn['connection'])===PGSQL_CONNECTION_OK) {
    call_hooks("postgresql_restart_done", $conn);
  }
}

register_hook("sql_connection_failed", "postgresql_restart_do");
