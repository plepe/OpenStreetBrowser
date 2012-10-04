<?
function db_init() {
  global $plugins;
  global $plugins_dir;
  $plugins_db=array();

  $res=sql_query("select * from plugins");
  while($elem=pg_fetch_assoc($res)) {
    $plugins_db[$elem['id']]=$elem;
  }

  foreach($plugins as $plugin) {
    // get current list of tags (incl. currently loaded updates)
    if(!isset($plugins_db[$plugin]))
      $plugin_tags=new tags();
    else
      $plugin_tags=new tags(parse_hstore($plugins_db[$plugin]['osm_tags']));

    // build list of all updates -> array("20101010_1"=>array("sql"))
    // file_name->extensions
    $updates=array();
    if(file_exists("$plugins_dir/$plugin/update")) {
      $d=opendir("$plugins_dir/$plugin/update");
      while($f=readdir($d)) {
	if(substr($f, 0, 1)!=".") {
	  $p=explode(".", $f);
	  $updates[$p[0]][]=$p[1];
	}
      }
      closedir($d);

    }
    ksort($updates);

    // always reload functions
    if(is_dir("$plugins_dir/$plugin/functions")) {
      // it's a list of files: read, sort and load each one of them
      $files=array();
      $d=opendir("$plugins_dir/$plugin/functions");
      while($f=readdir($d)) {
	if(preg_match("/^[^.].*\.sql/", $f))
	  $files[]=$f;
      }

      sort($files);

      foreach($files as $file) {
	debug("Plugin '$plugin', (re-)loading functions/$file", "plugins");
	sql_query(file_get_contents("$plugins_dir/$plugin/functions/$file"));
      }
    }
    elseif(file_exists("$plugins_dir/$plugin/functions.sql")) {
      // it's a single file -> load
      debug("Plugin '$plugin', (re-)loading functions.sql", "plugins");
      sql_query(file_get_contents("$plugins_dir/$plugin/functions.sql"));
    }

    if((function_exists("{$plugin}_db_init"))&&
       (!isset($plugins_db[$plugin]))) {
      debug("Plugin '$plugin', calling db_init-function", "plugins");
      call_user_func("{$plugin}_db_init");
    }

    if(file_exists("$plugins_dir/$plugin/db.sql")) {
      // If plugin has never been loaded before, load db.sql
      if(!isset($plugins_db[$plugin])) {
	debug("Plugin '$plugin', initializing db", "plugins");
	sql_query(file_get_contents("$plugins_dir/$plugin/db.sql"));
      }
      // load all missing updates
      else {
	$updates_done=explode(";", $plugin_tags->get("updates"));
	foreach($updates as $update=>$files) {
	  if(!in_array($update, $updates_done)) {
	    debug("Plugin '$plugin', loading update $update", "plugins");
	    if(in_array("sql", $files))
	      sql_query(file_get_contents("$plugins_dir/$plugin/update/$update.sql"));
	  }
	}
      }
    }

    // save update information to database
    $pg_plugin=postgre_escape($plugin);
    $plugin_tags->set("updates", implode(";", array_keys($updates)));
    $pg_tags=array_to_hstore($plugin_tags->data());
    sql_query("delete from plugins where id=$pg_plugin");
    sql_query("insert into plugins values ($pg_plugin, $pg_tags)");
  }
}

register_hook("mcp_start", "db_init");
