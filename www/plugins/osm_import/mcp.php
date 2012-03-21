<?
function osm_import_init() {
  global $osmosis_path;
  global $osm_import_source;

  global $db_osmosis;
  global $db_central;
  global $tmp_dir;

  $res=sql_query("select * from pg_tables where schemaname='!schema:osmosis!' and tablename='nodes'");
  if(pg_num_rows($res)) {
    debug("osm-data has already been imported", "osm_import", D_DEBUG);
    return;
  }

  if(!$osm_import_source) {
    debug("set \$osm_import_source in conf.php to an osm-file", "osm_import", D_ERROR);
    exit;
  }

  if(!$osmosis_path) {
    debug("set \$osmosis_path in conf.php to the root of an osmosis-installation", "osm_import", D_ERROR);
    exit;
  }

  debug("database not initialized yet -> import", "osm_import", D_WARNING);

  // remember search_path and set to 'osm'
  $res=sql_query("show search_path", $db_osmosis);
  $elem=pg_fetch_array($res);
  $search_path=$elem[0];
  sql_query("set search_path to !schema:osmosis!, !schema:this!, public", $db_osmosis);

  // load schema
  sql_query(file_get_contents("$osmosis_path/script/pgsimple_schema_0.6.sql"), $db_osmosis);
  sql_query(file_get_contents("$osmosis_path/script/pgsimple_schema_0.6_action.sql"), $db_osmosis);

  // download file to tmp_dir
  if(preg_match("/^[a-z]+:\/\//", $osm_import_source)) {
    $f1=fopen($osm_import_source, "r");
    $f2=fopen("$tmp_dir/data.osm", "w");
    while($r=fgets($f1, 1024*1024*32))
      fputs($f2, $r);
    fclose($f1);
    fclose($f2);
    $osm_import_source="$tmp_dir/data.osm";
  }

  // import via osmosis
  debug("starting osmosis, source: $osm_import_source", "osm_import", D_WARNING);
  system("osmosis --read-xml file=$osm_import_source --write-pgsimp host={$db_central['host']} user={$db_central['user']} password={$db_central['passwd']} database={$db_central['name']}");

  // reset search_path
  sql_query("set search_path to {$search_path}", $db_osmosis);

  // calculate osm tables
  debug("initialize osm_-tables", "osm_import", D_WARNING);
  sql_query("select osm_import_init()", $db_central);

  debug("done", "osm_import", D_WARNING);

  call_hooks("osm_import_done");
}

register_hook("mcp_start", "osm_import_init");
