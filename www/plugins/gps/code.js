var gps_object=null;

function gps() {
  if(navigator.geolocation)
    this.watch=navigator.geolocation.watchPosition(this.update);
}

gps.prototype.update=function(lonlat) {
  this.pos=new OpenLayers.LonLat(lonlat.coords.longitude, lonlat.coords.latitude);

  if(this.vector) {
    vector_layer.removeFeatures([this.vector]);
  }

  var pos = new clone(this.pos);
  pos.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
  var geo_point=new OpenLayers.Geometry.Point(pos.lon, pos.lat);
  this.vector=new OpenLayers.Feature.Vector(geo_point, 0, {
    externalGraphic: "plugins/gps/icon.png",
    graphicWidth: 25,
    graphicHeight: 25,
    graphicXOffset: -13,
    graphicYOffset: -20
  });
  vector_layer.addFeatures([this.vector]);

  if(first_load) {
    map.setCenter(pos, 14);
    first_load=1;
  }

  this.last_pos=this.pos;
}

function gps_init() {
  gps_object=new gps();
}

register_hook("init", gps_init);
