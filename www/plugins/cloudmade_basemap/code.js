function cloudmade_basemap_init() {
  var cloudmade = new OpenLayers.Layer.CloudMade("CloudMade", {
      key: key_cloudmade_api,
      styleId: 1
    });

  register_basemap("cloudmade-1", cloudmade);
}

register_hook("init", cloudmade_basemap_init);
