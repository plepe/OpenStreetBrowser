<?
global $design_hidden;
if(!$design_hidden) {
  print "<script type='text/javascript' src='plugins/cloudmade_basemap/cloudmade.js'></script>\n";
}

function cloudmade_basemap_init() {
  global $key_cloudmade_api;
  global $design_hidden;

  if(!$design_hidden)
    print '<script type="text/javascript" src="plugins/cloudmade_basemap/cloudmade.js"></script>\n';
  html_export_var(array("key_cloudmade_api"=>$key_cloudmade_api));
}

register_hook("init", "cloudmade_basemap_init");
