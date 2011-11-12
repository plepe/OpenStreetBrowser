<?

function cloudmade_basemap_init() {
  global $key_cloudmade_api;

  html_export_var(array("key_cloudmade_api"=>$key_cloudmade_api));
}

plugins_include_file("cloudmade_basemap", "cloudmade.js");
