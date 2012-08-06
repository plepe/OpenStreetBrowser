var layer_grid_overlay;
var layer_grid_activated=false;
var layer_grid_features={ meridians: {}, latitude_circle: {}, meridians_labels: {}, latitude_labels: {} };
var layer_grid_cur_zoom=0;
var layer_grid_major_style={ stroke: true, strokeColor: '#000000', strokeWidth: 2, strokeOpacity: 0.5 };
var layer_grid_minor_style={ stroke: true, strokeColor: '#000000', strokeWidth: 1, strokeOpacity: 0.5 };
var layer_grid_zoom=[ // major_inc, inc, label precision
  [ 180, 45, 0 ], // 0
  [ 180, 45, 0 ], // 1
  [  90, 30, 0 ], // 2 
  [  90, 22.5, 0 ], // 3
  [  50, 10, 0 ], // 4
  [  25, 5, 0 ], // 5
  [  10, 2, 0 ], // 6
  [   5, 1, 0 ], // 7
  [ 2.5, 0.5, 1 ], // 8
  [   1, 0.2, 1 ], // 9
  [   1, 0.2, 1 ], // 10
  [ 0.5, 0.1, 1 ], // 11
  [ 0.25, 0.05, 2 ], // 12
  [ 0.1, 0.02, 2 ], // 13
  [ 0.05, 0.01, 2 ], // 14
  [ 0.025, 0.005, 3 ], // 15
  [ 0.01, 0.002, 3 ], // 16
  [ 0.01, 0.002, 3 ], // 17
  [ 0.005, 0.001, 3 ], // 18
  [ 0.0025, 0.0005, 4 ] // 19
];
var layer_grid_meridian_major_label_style={ labelAlign: 'ct', labelYOffset: -4, labelOutlineWidth: 1, labelOutlineColor: 'white', externalGraphic: 'plugins/layer_grid/background.png', graphicHeight: 14, graphicWidth: 40, graphicYOffset: 1, graphicOpacity: 0.75, fontFamily: "Tahoma, Arial, Verdana", fontWeight: "bold", fontSize: "11px" };
var layer_grid_latitude_major_label_style={ labelAlign: 'rm', labelXOffset: -4, labelOutlineWidth: 1, labelOutlineColor: 'white', externalGraphic: 'plugins/layer_grid/background.png', graphicHeight: 14, graphicWidth: 40, graphicXOffset: -41, graphicOpacity: 0.75, fontFamily: "Tahoma, Arial, Verdana", fontWeight: "bold", fontSize: "11px" };
var layer_grid_meridian_minor_label_style={ labelAlign: 'ct', labelYOffset: -4, labelOutlineWidth: 1, labelOutlineColor: 'white', externalGraphic: 'plugins/layer_grid/background.png', graphicHeight: 14, graphicWidth: 40, graphicYOffset: 1, graphicOpacity: 0.6, fontFamily: "Tahoma, Arial, Verdana", fontWeight: "normal", fontSize: "11px" };
var layer_grid_latitude_minor_label_style={ labelAlign: 'rm', labelXOffset: -4, labelOutlineWidth: 1, labelOutlineColor: 'white', externalGraphic: 'plugins/layer_grid/background.png', graphicHeight: 14, graphicWidth: 40, graphicXOffset: -41, graphicOpacity: 0.6, fontFamily: "Tahoma, Arial, Verdana", fontWeight: "normal", fontSize: "11px" };

function layer_grid_remove() {
  layer_grid_overlay.removeFeatures(values(layer_grid_features.meridians));
  layer_grid_overlay.removeFeatures(values(layer_grid_features.latitude_circle));
  layer_grid_overlay.removeFeatures(values(layer_grid_features.meridians_labels));
  layer_grid_overlay.removeFeatures(values(layer_grid_features.latitude_labels));
  layer_grid_features={ meridians: {}, latitude_circle: {}, meridians_labels: {}, latitude_labels: {} };
}

function layer_grid_show_latitude(i, vp, conf) {
  var index=sprintf("%.5f", i);

  var pos1 = new OpenLayers.LonLat(vp.left, i).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo1 = new OpenLayers.Geometry.Point(pos1.lon, pos1.lat);
  var pos2 = new OpenLayers.LonLat(vp.right, i).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo2 = new OpenLayers.Geometry.Point(pos2.lon, pos2.lat);
  var geop = new OpenLayers.Geometry.Point(pos2.lon-0.0001, pos2.lat);

  // only move latitude line/label, then return
  if(layer_grid_features.latitude_circle[index]) {
    var m=layer_grid_features.latitude_circle[index];
    m.geometry.components[0].move(-m.geometry.components[0].x+geo1.x, 0);
    m.geometry.components[1].move(-m.geometry.components[1].x+geo2.x, 0);

    if(layer_grid_features.latitude_labels[index]) {
      var m=layer_grid_features.latitude_labels[index];
      m.geometry.move(-m.geometry.x+geop.x, 0);
    }

    return;
  }

  var geo=new OpenLayers.Geometry.LineString([ geo1, geo2 ]);

  var style=layer_grid_minor_style;
  var label_style=layer_grid_latitude_minor_label_style;

  var is_major=i%conf[0]; // conf[0]: major_inc
  if((Math.abs(is_major)<0.000001)||(Math.abs(major_inc-is_major)<0.000001)) {
    style=layer_grid_major_style;
    label_style=layer_grid_latitude_major_label_style;
  }

  // line
  layer_grid_features.latitude_circle[index]=new OpenLayers.Feature.Vector(geo, 0, style);

  // label
  label_style=new clone(label_style);
  label_style.label=sprintf("%."+conf[2]+"f°", i);
  layer_grid_features.latitude_labels[index]=
    new OpenLayers.Feature.Vector(geop, 0, label_style);
}

function layer_grid_show_meridian(i, vp, conf) {
  var index=sprintf("%.5f", i);

  var pos1 = new OpenLayers.LonLat(i, vp.bottom).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo1 = new OpenLayers.Geometry.Point(pos1.lon, pos1.lat);
  var pos2 = new OpenLayers.LonLat(i, vp.top).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo2 = new OpenLayers.Geometry.Point(pos2.lon, pos2.lat);
  var geop = new OpenLayers.Geometry.Point(pos2.lon, pos2.lat-0.0001);

  // only move meridian line/label, then return
  if(layer_grid_features.meridians[index]) {
    var m=layer_grid_features.meridians[index];
    m.geometry.components[0].move(0, -m.geometry.components[0].y+geo1.y);
    m.geometry.components[1].move(0, -m.geometry.components[1].y+geo2.y);

    if(layer_grid_features.meridians_labels[index]) {
      var m=layer_grid_features.meridians_labels[index];
      m.geometry.move(0, -m.geometry.y+geop.y);
    }

    return;
  }

  var geo=new OpenLayers.Geometry.LineString([ geo1, geo2 ]);

  var style=layer_grid_minor_style;
  var label_style=layer_grid_meridian_minor_label_style;

  var is_major=i%conf[0]; // conf[0]: major_inc
  if((Math.abs(is_major)<0.000001)||(Math.abs(major_inc-is_major)<0.000001)) {
    style=layer_grid_major_style;
    label_style=layer_grid_meridian_major_label_style;
  }

  // line
  layer_grid_features.meridians[index]=new OpenLayers.Feature.Vector(geo, 0, style);

  // label
  var label_style=new clone(label_style);
  label_style.label=sprintf("%."+conf[2]+"f°", i);
  layer_grid_features.meridians_labels[index]=
    new OpenLayers.Feature.Vector(geop, 0, label_style);
}

function layer_grid_view_changed() {
  if(!layer_grid_overlay.visibility)
    return;

  var proj=new OpenLayers.Projection("EPSG:4326");
  var vp=map.getExtent().transform(map.getProjectionObject(), proj);

  if(layer_grid_cur_zoom!=map.zoom) {
    layer_grid_remove();
    layer_grid_cur_zoom=map.zoom;
  }

  // get configuration for current zoom level
  var conf=layer_grid_zoom[map.zoom];
  major_inc=conf[0];
  inc=conf[1];

  // calculate position of first meridian
  var left=(vp.left-vp.left%inc);
  if(left>0)
    left+=inc;
  console.log(left+" "+vp.left+" "+vp.right);

  for(var i=left; i<vp.right; i+=inc)
    layer_grid_show_meridian(i, vp, conf);

  // calculate position of inner-most circle of latitude
  var inner;
  if((vp.bottom<0)&&(vp.top>0))
    inner=0;
  else if(vp.top<=0)
    inner=vp.top-vp.top%inc;
  else
    inner=vp.bottom-vp.bottom%inc;

  for(var i=inner; i<vp.top; i+=inc)
    layer_grid_show_latitude(i, vp, conf);

  for(var i=inner; i>vp.bottom; i-=inc)
    layer_grid_show_latitude(i, vp, conf);

  // add all features
  layer_grid_overlay.addFeatures(values(layer_grid_features.meridians));
  layer_grid_overlay.addFeatures(values(layer_grid_features.latitude_circle));
  layer_grid_overlay.addFeatures(values(layer_grid_features.meridians_labels));
  layer_grid_overlay.addFeatures(values(layer_grid_features.latitude_labels));
}

function layer_grid_activate() {
  if(!layer_grid_activated) {
    register_hook("view_changed", layer_grid_view_changed);
    layer_grid_activated=true;
  }

  layer_grid_view_changed();
}

function layer_grid_toggle() {
  layer_grid_overlay.setVisibility(!layer_grid_overlay.visibility);
}

function layer_grid_init() {
  layer_grid_overlay=new OpenLayers.Layer.Vector(
    lang("layer_grid:name"),
    {
      displayOutsideMaxExtent: false,
      isBaseLayer: false,
      transparent: true,
      visibility: false,
      weight: 0
    });
  layer_grid_overlay.events.register("visibilitychanged", layer_grid_overlay, layer_grid_activate);

  overlays_register("layer_grid", layer_grid_overlay, { help: lang("layer_grid:help") });

  if(typeof register_keyshort!="undefined")
    register_keyshort("layer_grid", layer_grid_toggle, "g", lang("layer_grid:toggle"));
}

register_hook("init", layer_grid_init);
