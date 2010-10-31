function hill_init() {
  register_layer("hill", 
    new OpenLayers.Layer.OSM(
      lang("hill:name"),
      "http://toolserver.org/~cmarqu/hill/", {
        type: 'png',
        displayOutsideMaxExtent: true,
        isBaseLayer: false,
        transparent: true,
        visibility: false 
      })
  );
}

register_hook("init", hill_init);
