function test_init() {
  alert("TEST");
  var test_layer = new OpenLayers.Layer.OSM("Test", "tiles/test_test/", {numZoomLevels: 19, isBaseLayer: false, visibility: false });
  map.addLayer(test_layer);
}

register_hook("init", test_init);
