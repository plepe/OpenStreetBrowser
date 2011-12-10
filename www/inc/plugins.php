<?
$plugins_list=array();
$plugins_include_files=array();
$plugins_dir="$root_path/www/plugins";

function plugins_include($plugin, $app) {
  global $plugins_list;
  global $plugins_include_files;
  global $plugins_dir;

  if((!file_exists("$plugins_dir/$plugin"))||
     (!file_exists("$plugins_dir/$plugin/conf.php"))) {
    debug("Including plugin '$plugin': No such plugin", "plugins");
    return false;
  }

  $var_active="{$plugin}_active";
  $var_depend="{$plugin}_depend";
  $var_tags="{$plugin}_tags";

  global $$var_active;
  global $$var_depend;
  global $$var_tags;

  include_once("$plugins_dir/$plugin/conf.php");

  if(!$$var_active)
    return false;

  if(file_exists("$plugins_dir/$plugin/$app.php"))
    $plugins_include_files[$plugin][]="$app.php";

  if(is_array($$var_depend))
    foreach($$var_depend as $inc) {
      if(!isset($plugins_list[$inc])) {
	if(!plugins_include($inc, $app))
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
  global $plugins;
  $plugins_list=array();

  $d=opendir("$plugins_dir/");
  foreach($plugins as $plugin) {
    plugins_include($plugin, $app);
  }

  $plugins=array_keys($plugins_list);

  closedir($d);
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
