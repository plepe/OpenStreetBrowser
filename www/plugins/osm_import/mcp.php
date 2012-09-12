<?
function osm_import_init() {
  global $osmosis_path;
  global $osmosis_db;
  global $osm_import_source;
  global $db_central;
  global $tmp_dir;
  global $plugins_dir;

  $res=sql_query("select * from pg_tables where schemaname='osm' and tablename='nodes'");
  if(pg_num_rows($res))
    return;

  if(!$osm_import_source) {
    debug("set \$osm_import_source in conf.php to an osm-file", "osm_import");
    return;
  }

  if(!$osmosis_path) {
    debug("set \$osmosis_path in conf.php to the root of an osmosis-installation", "osm_import");
    return;
  }

  debug("database not initialized yet -> import", "osm_import");

  if(!$osmosis_db)
    $osmosis_db=$db_central;

  // remember search_path and set to 'osm'
  //$res=sql_query("show search_path");
  //$elem=pg_fetch_array($res);
  //$search_path=$elem[0];
  //sql_query("set search_path to {$osmosis_db['user']}, {$db_central['user']}, public");

  // load schema
  sql_query(file_get_contents("$osmosis_path/script/pgsimple_schema_0.6.sql"));

  // create tmp_dir
  mkdir("$tmp_dir/pgimport");
  system("osmosis --read-xml file=$osm_import_source --write-pgsimp host={$osmosis_db['host']} user={$osmosis_db['user']} password={$osmosis_db['passwd']} database={$osmosis_db['name']}");

  // reset search_path
  //sql_query("set search_path to {$search_path}");

  // load db.sql which generates osm_point etc. tables
  debug("initializing database", "osm_import", D_NOTICE);
  sql_query(file_get_contents("$plugins_dir/osm_import/init.sql"));
}

register_hook("mcp_start", "osm_import_init");
