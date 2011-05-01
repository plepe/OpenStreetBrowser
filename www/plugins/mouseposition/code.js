function mouseposition_init() {
  map.addControl(new OpenLayers.Control.MousePosition());
}

register_hook("init", mouseposition_init);
