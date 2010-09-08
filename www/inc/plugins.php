<?
$plugins_list=array();

function plugins_include($plugin) {
  global $plugins_list;

  if((!file_exists("plugins/$plugin"))&&
     (!file_exists("plugins/$plugin/conf.php")))
    return false;

  include_once("plugins/$plugin/conf.php");

  $var_active="{$plugin}_active";

  if(!$$var_active)
    return false;

  $var_depend="{$plugin}_depend";

  if(is_array($$var_depend))
    foreach($$var_depend as $inc) {
      if(!$plugins_list[$inc]) {
	if(!plugins_include($inc))
	  return false;
      }
    }

  $var_tags="{$plugin}_tags";
  $plugins_list[$plugin]=$$var_tags;

  if(file_exists("plugins/$plugin/code.php"))
    include_once("plugins/$plugin/code.php");

  return true;
}

function plugins_html_head($plugin) {
  global $plugins_list;

  foreach($plugins_list as $plugin=>$tags) {
    if(file_exists("plugins/$plugin/code.js"))
      print "<script type='text/javascript' src='plugins/$plugin/code.js'></script>\n";
    if(file_exists("plugins/$plugin/style.css"))
      print "<link rel='stylesheet' type='text/css' href=\"plugins/$plugin/style.css\">\n";
  }
}

function plugins_init() {
  global $plugins_list;
  global $plugins;
  $plugins_list=array();

  $d=opendir("plugins/");
  foreach($plugins as $plugin) {
    plugins_include($plugin);
  }

  closedir($d);
}

register_hook("html_done", "plugins_html_head");
