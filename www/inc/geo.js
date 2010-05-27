var geo_pos;
var geo_vector;
var geo_watch;
var last_pos=null;

function update_lonlat(lonlat) {
  geo_pos=lonlat;

  if(geo_vector) {
    drag_layer.removeFeatures([geo_vector]);
  }

  var geo_point=new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);
  geo_vector=new OpenLayers.Feature.Vector(geo_point, 0, {
    externalGraphic: "mypos.png",
    graphicWidth: 25,
    graphicHeight: 25,
    graphicXOffset: -13,
    graphicYOffset: -20
  });
  drag_layer.addFeatures([geo_vector]);

  if(first_load) {
    map.setCenter(lonlat, 14);
    first_load=1;
  }

  if(showing_details) {
    redraw();
  }
}

function update_pos(pos) {
  if((!last_pos)||
     (pos.coords.longitude!=last_pos.longitude)||
     (pos.coords.latitude!=last_pos.latitude)) {
    var lonlat = new OpenLayers.LonLat(pos.coords.longitude, pos.coords.latitude).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    update_lonlat(lonlat);
   last_pos=pos.coords;
  }

//  alert("Geolocation: "+pos.coords.longitude+" "+pos.coords.latitude+" "+pos.coords.accuracy);
//  if(first_load) {
//    start_lon=pos.coords.longitude;
//    start_lat=pos.coords.latitude;
//    start_zoom=16;
//  }
}

function move_pos(feature, pos) {
  if(feature!=geo_vector)
    return;

  geo_pos=map.getLonLatFromPixel(pos);
  
  if(navigator.geolocation)
    navigator.geolocation.clearWatch(geo_watch);

  redraw();
//  update_lonlat(pos);
}

function geo_init() {
  if(my_pos)
    update_lonlat(new OpenLayers.LonLat(my_pos.lon, my_pos.lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject()));

  if(navigator.geolocation)
    geo_watch=navigator.geolocation.watchPosition(update_pos);
}


register_hook("finish_drag", move_pos);
//register_hook("post_init", geo_init);
