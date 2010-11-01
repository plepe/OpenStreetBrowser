function contour_init() {
  overlays_register("contour",
    new OpenLayers.Layer.OSM(
      "Contourshading",
      "http://hills-nc.openstreetmap.de/", {
        type: 'png',
        numZoomLevels: 16,
        displayOutsideMaxExtent: true,
        isBaseLayer: false,
        transparent: true,
        visibility: false,
        weight: -5
      })
  );
}

register_hook("init", contour_init);
