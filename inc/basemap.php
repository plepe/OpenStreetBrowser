<?
function basemap_init() {
  global $basemaps;

  html_export_var(array("basemaps"=>$basemaps));
}

register_hook("init", "basemap_init");
