var basemaps;

function basemap_init() {
  if(!basemaps) {
    basemaps={};
    basemaps.mapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
    map.addLayer(basemaps.mapnik);

    return;
  }

  for(var i in basemaps) {
    switch(basemaps[i]) {
      case "mapnik":
        basemaps[i]=new OpenLayers.Layer.OSM.Mapnik(t("basemap:"+i));
        break;
      case "osmarender":
        basemaps[i]=new OpenLayers.Layer.OSM.Osmarender(t("basemap:"+i));
        break;
      case "cyclemap":
        basemaps[i]=new OpenLayers.Layer.OSM.CycleMap(t("basemap:"+i));
        break;
      default:
        basemaps[i]=new OpenLayers.Layer.OSM(t("basemap:"+i), basemaps[i][0], basemaps[i][1]);
    }

    map.addLayer(basemaps[i]);
  }
}

register_hook("basemap_init", basemap_init);
