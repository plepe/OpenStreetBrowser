function hill_init() {
  overlays_register("hill", 
    new OpenLayers.Layer.OSM(
      "Hillshading (NASA SRTM3 v2)",
      "http://toolserver.org/~cmarqu/hill/", {
        type: 'png',
        displayOutsideMaxExtent: true,
        isBaseLayer: false,
        transparent: true,
        visibility: false,
        weight: -5
      })
  );
}

register_hook("init", hill_init);
