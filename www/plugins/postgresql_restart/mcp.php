<?
function postgresql_restart_do($conn) {
  debug("Restarting PostgreSQL server", "postgresql_restart");

  // if not localhost connection, ignore
  if($conn['host']!="localhost")
    return;

  // restart via sudo
  system("sudo /etc/init.d/postgresql restart");

  // try to connect (again)
  $conn['connection']=
    pg_connect("dbname={$conn['name']} user={$conn['user']} password={$conn['passwd']} host={$conn['host']}");
}

register_hook("sql_connection_failed", "postgresql_restart_do");
