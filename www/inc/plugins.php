<?
$plugins_available=array();
$plugins_list=array();
$plugins_include_files=array();
$plugins_dir="$root_path/www/plugins";

function plugins_load_conf($plugin) {
  global $plugins_available;
  global $plugins_dir;

  if((!file_exists("$plugins_dir/$plugin"))||
     (!file_exists("$plugins_dir/$plugin/conf.php"))) {
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

  include_once("$plugins_dir/$plugin/conf.php");

  if(!$$var_conflict)
    $$var_conflict=array();

  $plugins_available[$plugin]=array(
    'active'	=>$$var_active,
    'depend'	=>$$var_depend,
    'conflict'	=>$$var_conflict,
    'tags'	=>$$var_tags,
  );
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

  if(!$$var_active)
    return false;

  if(file_exists("$plugins_dir/$plugin/$app.php"))
    $plugins_include_files[$plugin][]="$app.php";

  if(is_array($$var_depend))
    foreach($$var_depend as $inc) {
      if(!isset($plugins_list[$inc])) {
	if(!plugins_include($inc, $app))
	  debug("Including plugin '$plugin': Cannot include dependency '$inc' - deactivating", "plugins", D_ERROR);
	  return false;
      }
    }

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
}

function plugins_init($app="code") {
  global $plugins_dir;
  global $plugins_list;
  global $plugins_available;
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
