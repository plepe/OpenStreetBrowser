<?

function cloudmade_basemap_init() {
  global $key_cloudmade_api;

  html_export_var(array("key_cloudmade_api"=>$key_cloudmade_api));
}

register_hook("init", "cloudmade_basemap_init");
plugins_include_file("cloudmade_basemap", "cloudmade.js");
