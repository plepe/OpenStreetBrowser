var basemaps;

function basemap_init() {
  if(!basemaps) {
    basemaps={};
    basemaps.mapnik = new ol.layer.Tile({
          baselayer: true,
          source: new ol.source.OSM()
        });
    map.addLayer(basemaps.mapnik);

    return;
  }

  for(var i in basemaps) {
    switch(basemaps[i]) {
      case "mapnik":
        basemaps[i] = new ol.layer.Tile({
          source: new ol.source.OSM()
        });
        break;
      case "osmarender":
        basemaps[i]=new ol.layer.OSM.Osmarender(t("basemap:"+i));
        break;
      case "cyclemap":
        basemaps[i]=new ol.layer.OSM.CycleMap(t("basemap:"+i));
        break;
      default:
        basemaps[i] = new ol.layer.Tile({
          source: new ol.source.OSM(basemaps[i])
        });
    }

    basemaps[i].setProperties({ baselayer: true });
    map.addLayer(basemaps[i]);
  }

  layers_reorder();

  map.on("changebaselayer", basemap_change);
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
