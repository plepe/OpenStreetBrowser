var marker_list={};
var marker_overlay;

function marker_update(new_hash) {
  // no mlat / mlon in new_hash
  if(!(new_hash.mlat)||(!new_hash.mlon))
    return;

  // if we don't have an overlay for markers yet, create it
  if(!marker_overlay) {
    marker_overlay = new OpenLayers.Layer.Markers(t("marker_overlay"));
    map.addLayer(marker_overlay);
  }

  // get parts of mlat- and mlon-parameters
  var mlats=new_hash.mlat.split(/,/);
  var mlons=new_hash.mlon.split(/,/);

  for(var i=0; i<mlats.length; i++) {
    // if we already set this marker, ignore
    if(marker_list[mlats[i]+"|"+mlons[i]])
      continue;

    // create the new marker
    var size = new OpenLayers.Size(21,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('http://www.openstreetmap.org/openlayers/img/marker.png', size, offset);
    var marker = new OpenLayers.Marker(new OpenLayers.LonLat(mlons[i], mlats[i]).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject()), icon);
    marker_overlay.addMarker(marker);

    // save marker in marker_list
    marker_list[mlats[i]+"|"+mlons[i]]=marker;
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
    mlats.push(pos[0]);
    mlons.push(pos[1]);
  }

  // if we don't have any markers in the list, return
  if(!mlats.length)
    return ;

  // save in permalink
  permalink.mlat=mlats.join(",");
  permalink.mlon=mlons.join(",");
}

register_hook("get_permalink", marker_permalink);
register_hook("hash_changed", marker_update);
