var measure_features={ meridians: {}, latitude_circle: {} };
var measure_cur_zoom=0;
var measure_grid_major_style={ stroke: true, strokeColor: '#000000', strokeWidth: 2, strokeOpacity: 0.5 };
var measure_grid_minor_style={ stroke: true, strokeColor: '#000000', strokeWidth: 1, strokeOpacity: 0.5 };

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
      major_inc=50;
      inc=10;
      break;
    case 5:
      major_inc=25;
      inc=5;
      break;
    case 6:
      major_inc=10;
      inc=2;
      break;
    case 7:
      major_inc=5;
      inc=1;
      break;
    case 8:
      major_inc=2.5;
      inc=0.5;
      break;
    case 9:
      major_inc=1;
      inc=0.2;
      break;
    default: return;
  }

  vp.left=(vp.left/inc).toFixed(0)*inc-inc;

  for(var i=vp.left; i<vp.right+inc; i+=inc) {
    var pos1 = new OpenLayers.LonLat(i, vp.bottom).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
    var geo1 = new OpenLayers.Geometry.Point(pos1.lon, pos1.lat);
    var pos2 = new OpenLayers.LonLat(i, vp.top).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
    var geo2 = new OpenLayers.Geometry.Point(pos2.lon, pos2.lat);

    if(measure_features.meridians[i]) {
      var m=measure_features.meridians[i];
      m.geometry.components[0].move(0, -m.geometry.components[0].y+geo1.y);
      m.geometry.components[1].move(0, -m.geometry.components[1].y+geo2.y);
      continue;
    }

    var geo=new OpenLayers.Geometry.LineString([ geo1, geo2 ]);

    style=measure_grid_minor_style;
    var is_major=i%major_inc;
    if((Math.abs(is_major)<0.0000001)||(Math.abs(major_inc-is_major)<0.0000001))
      style=measure_grid_major_style;

    measure_features.meridians[i]=new OpenLayers.Feature.Vector(geo, 0, style);
  }

  vector_layer.addFeatures(values(measure_features.meridians));
}

register_hook("view_changed", measure_grid_view_changed);
