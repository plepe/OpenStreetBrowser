<?
// TODO: This value is also defined in the file www/inc/categories.php . It
// should be removed there, as soon as categories are converted to plugins.
global $importance_levels;
$importance_levels=array("global", "international", "national", "regional", "urban", "suburban", "local");

// TODO: This values are not used yet, but the variables $scale_icon and
// $scale_text in www/inc/categories.php . This should be changed sometime.
global $importance_zoom;
$importance_zoom=array(
  "global"        =>array("list"=> 1, "icon"=> 2, "label"=> 4),
  "international" =>array("list"=> 4, "icon"=> 5, "label"=> 8),
  "national"	  =>array("list"=> 7, "icon"=> 8, "label"=>10),
  "regional"	  =>array("list"=>10, "icon"=>11, "label"=>13),
  "urban"	  =>array("list"=>12, "icon"=>13, "label"=>15),
  "suburban"	  =>array("list"=>14, "icon"=>15, "label"=>16),
  "local" 	  =>array("list"=>16, "icon"=>17, "label"=>18),
);

function importance_init() {
  global $importance_levels;
  global $importance_zoom;

  html_export_var(array(
    "importance_levels" =>$importance_levels,
    "importance_zoom"   =>$importance_zoom,
  ));
}

register_hook("init", "importance_init");
