var marker_list={};
var marker_overlay;

function marker_update(new_hash) {
  // no mlat / mlon in new_hash
  if(!(new_hash.mlat)||(!new_hash.mlon))
    return;

  // get parts of mlat- and mlon-parameters
  var mlats=new_hash.mlat.split(/,/);
  var mlons=new_hash.mlon.split(/,/);

  for(var i=0; i<mlats.length; i++) {
    // if we already set this marker, ignore
    if(marker_list[mlons[i]+"|"+mlats[i]])
      continue;

    // add marker
    marker_add(mlons[i], mlats[i]);
  }
}

function marker_permalink(permalink) {
  // initalize empty arrays
  var mlats=[];
  var mlons=[];

  // for each element in marker_list
  for(var i in marker_list) {
    var pos=i.split(/\|/);

    // push them to the arrays
    mlons.push(pos[0]);
    mlats.push(pos[1]);
  }

  // if we don't have any markers in the list, return
  if(!mlats.length)
    return ;

  // save in permalink
  permalink.mlat=mlats.join(",");
  permalink.mlon=mlons.join(",");
}

function marker_add(lon, lat) {
  // if we don't have an overlay for markers yet, create it
  if(!marker_overlay) {
    marker_overlay = new OpenLayers.Layer.Markers(t("overlay:marker"));
    map.addLayer(marker_overlay);
  }

  // create the new marker
  var size = new OpenLayers.Size(21,25);
  var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
  var icon = new OpenLayers.Icon('http://www.openstreetmap.org/openlayers/img/marker.png', size, offset);
  var marker = new OpenLayers.Marker(new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject()), icon);
  marker_overlay.addMarker(marker);

  // save marker in marker_list
  marker_list[lon+"|"+lat]=marker;

  // force an update of the permalink
  update_permalink();
}

function marker_add_context(pos) {
  // add a marker on the pos
  marker_add(pos.lon, pos.lat);
}

function marker_init() {
  contextmenu_add("img/toolbox_marker.png", "add marker", marker_add_context);
}

register_hook("get_permalink", marker_permalink);
register_hook("hash_changed", marker_update);
register_hook("init", marker_init);
