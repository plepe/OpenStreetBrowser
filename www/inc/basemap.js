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
    basemaps[i].wrapDateLine=true;

    map.addLayer(basemaps[i]);
  }

  layers_reorder();

  map.events.register("changebaselayer", map, basemap_change);
}

function basemap_change() {
  call_hooks("basemap_changebaselayer", map.baseLayer);
}

function register_basemap(id, layer) {
  basemaps[id]=layer;

  map.addLayer(layer);

  layers_reorder();

  call_hooks("basemap_registered", layer, id);
}

function basemap_permalink(permalink) {
  for(var i in basemaps) {
    if(map.baseLayer==basemaps[i])
      permalink.basemap=i;
  }
}

function basemap_hash_changed(new_hash) {
  if(!new_hash.basemap)
    return;

  if(basemaps[new_hash.basemap])
    map.setBaseLayer(basemaps[new_hash.basemap]);
}

register_hook("init", basemap_init);
register_hook("get_permalink", basemap_permalink);
register_hook("hash_changed", basemap_hash_changed);
