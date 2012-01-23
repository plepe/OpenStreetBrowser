var measure_features={ meridians: {}, latitude_circle: {} };
var measure_cur_zoom=0;

function measure_grid_view_changed() {
  var proj=new OpenLayers.Projection("EPSG:4326");
  var vp=map.getExtent().transform(map.getProjectionObject(), proj);

  if(measure_cur_zoom!=map.zoom) {
    vector_layer.removeFeatures(values(measure_features.meridians));
    vector_layer.removeFeatures(values(measure_features.latitude_circle));
    measure_features={ meridians: {}, latitude_circle: {} };
    measure_cur_zoom=map.zoom;
  }

  switch(map.zoom) {
    case 4:
      major_inc=10;
      minor_inc=5;
      vp.left=(vp.left/10).toFixed(0)*10-5;
      inc=5;
      break;
    default: return;
  }

  for(var i=vp.left; i<vp.right+5; i+=inc) {
    if(measure_features.meridians[i])
      continue;

    var pos1 = new OpenLayers.LonLat(i, -89.999).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
    var geo1 = new OpenLayers.Geometry.Point(pos1.lon, pos1.lat);
    var pos2 = new OpenLayers.LonLat(i, +89.999).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
    var geo2 = new OpenLayers.Geometry.Point(pos2.lon, pos2.lat);

    var geo=new OpenLayers.Geometry.LineString([ geo1, geo2 ]);
    measure_features.meridians[i]=new OpenLayers.Feature.Vector(geo); //, 0, marker_style);
  }

  vector_layer.addFeatures(values(measure_features.meridians));
}

register_hook("view_changed", measure_grid_view_changed);
