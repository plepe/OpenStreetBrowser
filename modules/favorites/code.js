var favorites_list={};
var favorites_toolbox;
var favorites_drag_control;

function favorites(lon, lat) {
  // finish_drag
  this.finish_drag=function(pos) {
    // calculate lonlat of new position
    var lonlat=map.getLonLatFromPixel(pos).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

    // save new position to marker_list
    delete favorites_list[this.lon+"|"+this.lat];
    this.lon=lonlat.lon;
    this.lat=lonlat.lat;
    favorites_list[this.lon+"|"+this.lat]=this;
    toolbox_favorites(1);
  }
  // constructor
  this.lon=lon;
  this.lat=lat;

  // create the new marker
  var pos = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo = new OpenLayers.Geometry.Point(pos.lon, pos.lat);
  this.feature = new OpenLayers.Feature.Vector(geo, 0, {
    externalGraphic: modulekit_file("favorites", "favorite.png"),
    graphicWidth: 23,
    graphicHeight: 24,
    graphicXOffset: -5,
    graphicYOffset: -24
  });
  drag_layer.addFeatures([this.feature]);
  this.feature.ob=this;

  // save marker in marker_list
  favorites_list[lon+"|"+lat]=this;

}

function favorites_toolbox_text() {
  var text="<i>Favorites</i><br/><br/>";
  var nbrfav=0;
  for(var i in favorites_list) {
    text+="favorite "+(nbrfav+1)+": "+favorites_list[i].lon +", "+favorites_list[i].lat+"<br/>";
    nbrfav++;
  }
  if (nbrfav==0) {
    text += "Set favorites by clicking on the map...";
  }
  text += "<br/><br/>";

  favorites_toolbox.content.innerHTML=text;
}

function favorites_add(lon, lat) {
  return new favorites(lon, lat);
}

function favorites_add_context(pos) {
  // add a marker on the pos
  favorites_add(pos.lon, pos.lat);
  
}

function favorites_init() {
  if(modulekit_loaded("contextmenu")) {
    contextmenu_add(modulekit_file("favorites", "icon.png"), "add favorite", favorites_add_context);
  }

  favorites_toolbox=new toolbox({
    icon: modulekit_file("favorites", "icon.png"),
    icon_title: "favorites",
    callback_activate: favorites_toolbox_text
  });

  register_toolbox(favorites_toolbox);
}

register_hook("init", favorites_init);
