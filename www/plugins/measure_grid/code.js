var measure_features={ meridians: {}, latitude_circle: {} };
var measure_cur_zoom=0;
var measure_grid_major_style={ stroke: true, strokeColor: '#000000', strokeWidth: 2, strokeOpacity: 0.5 };
var measure_grid_minor_style={ stroke: true, strokeColor: '#000000', strokeWidth: 1, strokeOpacity: 0.5 };
var measure_grid_zoom=[ // major_inc, inc
  [ 180, 45 ], // 0
  [ 180, 45 ], // 1
  [  90, 30 ], // 2 
  [  90, 22.5 ], // 3
  [  50, 10 ], // 4
  [  25, 5 ], // 5
  [  10, 2 ], // 6
  [   5, 1 ], // 7
  [ 2.5, 0.5 ], // 8
  [   1, 0.2 ], // 9
  [   1, 0.2 ], // 10
  [ 0.5, 0.1 ], // 11
  [ 0.25, 0.05 ], // 12
  [ 0.1, 0.02 ], // 13
  [ 0.05, 0.01 ], // 14
  [ 0.025, 0.005 ], // 15
  [ 0.01, 0.002 ], // 16
  [ 0.01, 0.002 ], // 17
  [ 0.005, 0.001 ], // 18
  [ 0.0025, 0.0005 ] // 19
];

function measure_grid_view_changed() {
  var proj=new OpenLayers.Projection("EPSG:4326");
  var vp=map.getExtent().transform(map.getProjectionObject(), proj);

  if(measure_cur_zoom!=map.zoom) {
    vector_layer.removeFeatures(values(measure_features.meridians));
    vector_layer.removeFeatures(values(measure_features.latitude_circle));
    measure_features={ meridians: {}, latitude_circle: {} };
    measure_cur_zoom=map.zoom;
  }

  // get configuration for current zoom level
  var conf=measure_grid_zoom[map.zoom];
  major_inc=conf[0];
  inc=conf[1];

  // calculate position of first meridian
  vp.left=(vp.left-vp.left%inc);
  if(vp.left>=0)
    vp.left+=inc;

  for(var i=vp.left; i<vp.right; i+=inc) {
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
