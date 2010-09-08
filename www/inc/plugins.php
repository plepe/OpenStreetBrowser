<?
$plugins_list=array();

function plugins_include($plugin) {
  if(file_exists("plugins/$plugin/code.php"))
    include_once("plugins/$plugin/code.php");
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
  foreach($plugins as $plugin)
    if((file_exists("plugins/$plugin"))&&
       (file_exists("plugins/$plugin/conf.php"))) {
    include_once("plugins/$plugin/conf.php");

    $var="{$plugin}_active";
    if($$var) {
      plugins_include($plugin);

      $var="{$plugin}_tags";
      $plugins_list[$plugin]=$$var;
    }
  }

  closedir($d);
}

register_hook("html_done", "plugins_html_head");
