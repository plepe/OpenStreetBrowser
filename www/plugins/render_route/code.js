function render_route_init() {
  var render_route_layer = new OpenLayers.Layer.OSM("Render Route", "tiles/render_route_overlay_pt/", {numZoomLevels: 19, isBaseLayer: false, visibility: false });
  map.addLayer(render_route_layer);
}

register_hook("init", render_route_init);
