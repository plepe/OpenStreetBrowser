<?
$plugins_available=array();
$plugins_list=array();
$plugins_include_files=array();
$plugins_dir="$root_path/www/plugins";
$plugins_provide=array();

function plugins_load_conf($plugin) {
  global $plugins_available;
  global $plugins_provide;
  global $plugins_dir;

  if((!file_exists("$plugins_dir/$plugin"))||
     (!file_exists("$plugins_dir/$plugin/conf.php"))) {
    return false;
  }

  $var_active="{$plugin}_active";
  $var_depend="{$plugin}_depend";
  $var_conflict="{$plugin}_conflict";
  $var_provide="{$plugin}_provide";
  $var_tags="{$plugin}_tags";

  global $$var_active;
  global $$var_depend;
  global $$var_conflict;
  global $$var_provide;
  global $$var_tags;

  include_once("$plugins_dir/$plugin/conf.php");

  // Default Values
  if(!isset($$var_active))
    $$var_active=true;
  if(!$$var_conflict)
    $$var_conflict=array();
  if(!isset($$var_provide))
    $$var_provide=array();
  elseif(is_string($$var_provide))
    $$var_provide=array($$var_provide);

  if($$var_tags)
    $tags=$$var_tags->data();
  else
    $tags=array();

  $plugins_available[$plugin]=array(
    'active'	=>$$var_active,
    'depend'	=>$$var_depend,
    'conflict'	=>$$var_conflict,
    'provide'	=>$$var_provide,
    'tags'	=>$tags,
  );

  foreach($$var_provide as $provide)
    $plugins_provide[$provide][]=$plugin;
}

function plugins_check_dependency($plugin, &$loaded) {
  global $plugins_available;
  global $plugins_provide;

  $var_active="{$plugin}_active";
  $var_depend="{$plugin}_depend";
  $var_conflict="{$plugin}_conflict";
  $var_tags="{$plugin}_tags";

  global $$var_active;
  global $$var_depend;
  global $$var_conflict;
  global $$var_tags;

  if(!isset($plugins_available[$plugin])) {
    debug("Including plugin '$plugin': No such plugin", "plugins", D_ERROR);
    return;
  }

  if($$var_active===false) {
    debug("Including plugin '$plugin': Plugin has been deactivated", "plugins", D_ERROR);
    return;
  }

  debug("Including plugin '$plugin': Check dependencies", "plugins", D_DEBUG);

  foreach($$var_depend as $dep) {
    if(isset($plugins_provide[$dep])) {
      if(sizeof($plugins_provide[$dep])==1) {
	debug("Including plugin '$plugin': Including dependency '$dep' - choosing '{$plugins_provide[$dep][0]}' instead", "plugins", D_WARNING);
	$dep=$plugins_provide[$dep][0];
      }
      else {
	$found=false;
	foreach($loaded as $p)
	  if(in_array($p, $plugins_provide[$dep]))
	    $found=$p;

	if($found) {
	  debug("Including plugin '$plugin': Dependency '$dep' provided by '$found'", "plugins", D_NOTICE);
	  $dep=$found;
	}
	else {
	  debug("Including plugin '$plugin': Cannot include dependency '$dep', provided by '".implode("' or '", $plugins_provide[$dep])."' - enable one of them", "plugins", D_ERROR);
	  return;
	}
      }
    }
    elseif(!isset($plugins_available[$dep])) {
      debug("Including plugin '$plugin': Cannot include dependency '$dep' - deactivating", "plugins", D_ERROR);
      return;
    }
    else {
      // dependency can be loaded by name
    }

    if(!in_array($dep, $loaded)) {
      plugins_check_dependency($dep, &$loaded);
    }
  }

  if(!in_array($plugin, $loaded))
    $loaded[]=$plugin;
}

function plugins_check_dependencies($plugins) {
  global $plugins_available;
  $loaded=array();

  foreach($plugins as $p) {
    plugins_check_dependency($p, &$loaded);
  }

  return $loaded;
}

function plugins_include($plugin, $app) {
  global $plugins_list;
  global $plugins_include_files;
  global $plugins_dir;
  global $plugins_available;

  if(!isset($plugins_available[$plugin])) {
    debug("Including plugin '$plugin': No such plugin", "plugins", D_WARNING);
    return false;
  }

  $var_active="{$plugin}_active";
  $var_depend="{$plugin}_depend";
  $var_conflict="{$plugin}_conflict";
  $var_tags="{$plugin}_tags";

  global $$var_active;
  global $$var_depend;
  global $$var_conflict;
  global $$var_tags;

  if(file_exists("$plugins_dir/$plugin/$app.php"))
    $plugins_include_files[$plugin][]="$app.php";

  $plugins_list[$plugin]=$$var_tags;

  if(isset($plugins_include_files[$plugin]))
    foreach($plugins_include_files[$plugin] as $file) {
      if(preg_match("/\.php$/", $file))
	include_once("$plugins_dir/$plugin/$file");
    }

  return true;
}

// returns false on error
function plugins_check() {
  global $plugins_list;

  // check conflicts
  $noerror=true;
  foreach($plugins_list as $plugin=>$tags) {
    $var_conflict="{$plugin}_conflict";
    global $$var_conflict;

    foreach($$var_conflict as $conflict) {
      if(isset($plugins_list[$conflict])) {
	debug("Plugin '$plugin': Conflict with plugin '$conflict', please deactivate.", "plugins", D_ERROR);
	$noerror=false;
      }
    }
  }

  return $noerror;
}

function plugins_html_head($plugin) {
  global $plugins_dir;
  global $plugins_list;
  global $plugins_include_files;
  global $plugins;
  global $version_string;
  global $plugins_available;
  $plugins_script="";
  $str="";

  foreach($plugins_list as $plugin=>$tags) {
    $var_active="{$plugin}_active";
    $var_depend="{$plugin}_depend";
    $var_tags="{$plugin}_tags";
    global $$var_active;
    global $$var_depend;
    global $$var_tags;

    if(!isset($$var_tags))
      $$var_tags=new tags(array());

    $plugins_script.="var {$var_active}=true;\n";
    $plugins_script.="var {$var_depend}=".html_var_to_js($$var_depend).";\n";
    $plugins_script.="var {$var_tags}=new tags(".html_var_to_js($$var_tags->data()).");\n";

    if(file_exists("$plugins_dir/$plugin/code.js"))
      $plugins_include_files[$plugin][]="code.js";
    if(file_exists("$plugins_dir/$plugin/style.css"))
      $plugins_include_files[$plugin][]="style.css";

    if(isset($plugins_include_files[$plugin]))
      foreach($plugins_include_files[$plugin] as $file) {
	if(preg_match("/\.js$/", $file))
	  $str.="<script type='text/javascript' src='plugins/$plugin/$file{$version_string}'></script>\n";
	if(preg_match("/\.css$/", $file))
	  $str.="<link rel='stylesheet' type='text/css' href=\"plugins/$plugin/$file{$version_string}\">\n";
      }
  }

  $plugins_script.="var plugins=".html_var_to_js($plugins).";\n";

  $plugins_script.="var plugins_list={\n  ";
  $list=array();
  foreach($plugins_list as $plugin=>$tags) {
    $list[]="$plugin: {$plugin}_tags";
  }
  $plugins_script.=implode(",\n  ", $list);
  $plugins_script.="\n};\n";

  print "<script type='text/javascript'>\n$plugins_script\n</script>\n";
  print $str;
  html_export_var(array("plugins_available"=>$plugins_available));
}

function plugins_init($app="code") {
  global $plugins_dir;
  global $plugins_list;
  global $plugins_available;
  global $plugins_provide;
  global $plugins;
  $plugins_list=array();

  // Load conf.php of all plugins
  $d=opendir("$plugins_dir/");
  while($plugin=readdir($d)) {
    if((substr($plugin, 0, 1)!=".")&&(is_dir("$plugins_dir/$plugin"))) {
      plugins_load_conf($plugin);
    }
  }
  closedir($d);

  // Check dependencies
  $plugins=plugins_check_dependencies($plugins);

  // Include plugins
  foreach($plugins as $plugin) {
    plugins_include($plugin, $app);
  }

  $plugins=array_keys($plugins_list);


  if(!plugins_check()) {
    print "Error loading plugins, see log for details.\n";
    exit;
  }
}

function plugins_include_file($plugin, $file) {
  global $plugins_include_files;

  $plugins_include_files[$plugin][]=$file;
}

/**
 * plugins_loaded - Checks if a plugin is loaded
 * @param string ID of plugin
 * @return boolean true if plugin has been loaded
 */
function plugins_loaded($plugin) {
  global $plugins;

  return in_array($plugin, $plugins);
}

register_hook("html_done", "plugins_html_head");
