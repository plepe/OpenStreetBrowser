function basemap_init() {
  var basemap_layer = new OpenLayers.Layer.OSM("Base Map", "tiles/basemap_base/", {numZoomLevels: 19, isBaseLayer: true });

  register_basemap("basemap", basemap_layer);
}

register_hook("init", basemap_init);
