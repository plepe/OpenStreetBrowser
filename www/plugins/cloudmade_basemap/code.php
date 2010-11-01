<script type="text/javascript" src="plugins/cloudmade_basemap/cloudmade.js"></script>
<?
function cloudmade_basemap_init() {
  global $key_cloudmade_api;

  html_export_var(array("key_cloudmade_api"=>$key_cloudmade_api));
}

register_hook("init", "cloudmade_basemap_init");
