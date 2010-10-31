var basemaps;

function basemap_init() {
  if(!basemaps) {
    basemaps={};
    basemaps.mapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
    map.addLayer(basemaps.mapnik);

    return;
  }
}

register_hook("init", basemap_init);
