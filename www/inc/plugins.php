<?
$plugins_list=array();

function plugins_include($plugin) {
  global $plugins_list;

  if((!file_exists("plugins/$plugin"))&&
     (!file_exists("plugins/$plugin/conf.php")))
    return false;

  $var_active="{$plugin}_active";
  $var_depend="{$plugin}_depend";
  $var_tags="{$plugin}_tags";

  global $$var_active;
  global $$var_depend;
  global $$var_tags;

  include_once("plugins/$plugin/conf.php");

  if(!$$var_active)
    return false;

  if(is_array($$var_depend))
    foreach($$var_depend as $inc) {
      if(!$plugins_list[$inc]) {
	if(!plugins_include($inc))
	  return false;
      }
    }

  $plugins_list[$plugin]=$$var_tags;

  if(file_exists("plugins/$plugin/code.php"))
    include_once("plugins/$plugin/code.php");

  return true;
}

function plugins_html_head($plugin) {
  global $plugins_list;
  global $plugins;
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

    if(file_exists("plugins/$plugin/code.js"))
      $str.="<script type='text/javascript' src='plugins/$plugin/code.js'></script>\n";
    if(file_exists("plugins/$plugin/style.css"))
      $str.="<link rel='stylesheet' type='text/css' href=\"plugins/$plugin/style.css\">\n";
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

function plugins_init() {
  global $plugins_list;
  global $plugins;
  $plugins_list=array();

  $d=opendir("plugins/");
  foreach($plugins as $plugin) {
    plugins_include($plugin);
  }

  $plugins=array_keys($plugins_list);

  closedir($d);
}

register_hook("html_done", "plugins_html_head");
