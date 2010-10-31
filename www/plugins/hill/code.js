function hill_init() {
  register_layer("hill", 
    new OpenLayers.Layer.OSM(
      "Hillshading (NASA SRTM3 v2)",
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
