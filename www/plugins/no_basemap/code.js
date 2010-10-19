function no_basemap_init() {
  var no_basemap_layer = new OpenLayers.Layer("No Basemap",{isBaseLayer: true,
    'displayInLayerSwitcher': true});
  map.addLayer(no_basemap_layer);
}

register_hook("init", no_basemap_init);
