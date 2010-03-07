var routing_vector=null;
var route_type="car";
var routing_end_vector;
var routing_end_pos;

function change_route_type() {
  route_type=document.getElementById("route_type").value;
  redraw();
}

function get_my_pos(event, pos) {
  click_override=null;
  update_lonlat(pos);

  navigator.geolocation.clearWatch(geo_watch);
}

function set_my_pos() {
  alert(t("geo_click_pos"));
  click_override=get_my_pos;
}

function routing_details(data, obj) {
  var poi_list=new Array();
  var el=data.getElementsByTagName("wpt");

  if(!geo_pos)
    return;

  if(el.length!=0) {
    // my pos
    poi_list.push(new OpenLayers.Geometry.Point(geo_pos.lon, geo_pos.lat));

    // waypoints
    for(var i=0; i<el.length; i++) {
      var lonlat = new OpenLayers.LonLat(el[i].getAttribute("lon"), el[i].getAttribute("lat")).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
      poi_list.push(new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat));
    }

    // end point
    el=data.getElementsByTagName("end_pos");
    var lonlat = new OpenLayers.LonLat(el[0].getAttribute("lon"), el[0].getAttribute("lat")).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    poi_list.push(new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat));
  }
//  poi_list.push(new OpenLayers.Geometry.Point(geo_pos.lon, geo_pos.lat));

  poi_list=new OpenLayers.Geometry.LineString(poi_list);
  routing_vector=new OpenLayers.Feature.Vector(poi_list, 0, {
    strokeWidth: 4,
    strokeColor: "blue"
  });
  vector_layer.addFeatures([routing_vector]);

  if(!routing_end_vector) {
    var el=data.getElementsByTagName("end_pos");
    if(el.length) {
      var lonlat = new OpenLayers.LonLat(el[0].getAttribute("lon"), el[0].getAttribute("lat")).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
      var geo_point=new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);
      routing_end_vector=new OpenLayers.Feature.Vector(geo_point, 0, {
	externalGraphic: "goal.png",
	graphicWidth: 25,
	graphicHeight: 25,
	graphicXOffset: -7,
	graphicYOffset: -22
      });
      drag_layer.addFeatures([routing_end_vector]);
    }
  }
}

function routing_req(param) {
  if(geo_pos) {
    param["pos_lat"]=geo_pos.lat;
    param["pos_lon"]=geo_pos.lon;
    if(routing_end_pos) {
      param["routing_end_lat"]=routing_end_pos.lat;
      param["routing_end_lon"]=routing_end_pos.lon;
    }
    param["route_type"]=route_type;
  }
}

function routing_hide() {
  if(routing_vector)
    vector_layer.removeFeatures([routing_vector]);
  routing_vector=null;
}

function routing_move(feature, pos) {
  if(feature!=routing_end_vector)
    return;

  routing_end_pos=map.getLonLatFromPixel(pos);
  redraw();
}

function routing_init() {
  routing_end_pos=null;
  if(routing_end_vector)
    drag_layer.removeFeatures([routing_end_vector]);
  routing_end_vector=null;
}

register_hook("load_details", routing_details);
register_hook("request_details", routing_req);
register_hook("hide_features", routing_hide);
register_hook("finish_drag", routing_move);
register_hook("hash_changed", routing_init);
