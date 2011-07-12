/* known bugs:
 * - by clicking on "clear" button last element of via not removed
 * - inverting route gives out errors
 * - home and destination icons are not yet changed when inverting the route
 * - drag and drop of the icons doesn't work yet
 */

var navigation_toolbox;
var myroute=new route();

var home_style={
    externalGraphic: 'plugins/navigation/home.png',
    graphicWidth: 26,
    graphicHeight: 22,
    graphicXOffset: -13,
    graphicYOffset: -22
  };
var via_style={
    externalGraphic: 'plugins/navigation/via.png',
    graphicWidth: 25,
    graphicHeight: 22,
    graphicXOffset: -16,
    graphicYOffset: -22
  };
var destination_style={
    externalGraphic: 'plugins/navigation/destination.png',
    graphicWidth: 23,
    graphicHeight: 23,
    graphicXOffset: -4,
    graphicYOffset: -23
  };

function navigation_point(lon, lat, style) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="marker";

  // geo_center
  this.geo_center=function() {
    return this.feature;
  }

  // remove
  this.remove=function() {
    drag_layer.unselect(this.feature);
    drag_layer.removeFeatures([this.feature]);
  }

  // constructor
  this.lon=parseFloat(lon);
  this.lat=parseFloat(lat);
  //this.id="";

  var pos = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo = new OpenLayers.Geometry.Point(pos.lon, pos.lat);
  this.feature = new OpenLayers.Feature.Vector(geo, 0, style);
  drag_layer.addFeatures([this.feature]);
  this.feature.ob=this;
}

function route() {
  this.via=new Array();
  this.route_type="car";
  this.route_type_modifier="";
  
  //changes route type
  this.change_route_type=function(obj){
    var temp=obj.options[obj.selectedIndex].value;
    if(temp=="car (shortest)") {
      this.route_type="car";
      this.route_type_modifier="shortest";
    } else {
      this.route_type=temp;
      this.route_type_modifier="";
    }
    calculate_route();
  }

  //inverts route
  this.invert=function(){
    var temp=this.home;
    this.home=this.destination;
    this.destination=temp;
    this.via.reverse();
    navigation_toolboxtext();
    calculate_route();
  }
  /*
  this.show=function(){
    
  }
  this.hide=function(){

  }

  this.finish_drag=function(pos){
    alert("fertig");
  }
  
  this.next_drag=function(pos){
    alert("test");

  */

  //removes the route
  this.remove=function(){
    this.remove_home();
    this.remove_destination();
    for(var i=0;i<this.via.length;i++){
      this.remove_via(i);
    }
  }

  //sets your home point
  this.set_home=function(point) {
    if(this.home) {
      this.home.remove();
    }
    this.home=point;

    navigation_toolboxtext();
  }

  //sets your destination point
  this.set_destination=function(point) {
    if(this.destination) {
      this.destination.remove();
    }
    this.destination=point;

    navigation_toolboxtext();
  }

  //adds a via point to the via array
  this.add_via=function(point) {
    this.via.push(point);

    navigation_toolboxtext();
  }

 
  //removes the i-th point from the via array
  this.remove_via=function(i){
    this.via[i].remove();
    this.via.splice(i, 1);
  }
}

function navigation_set_home(pos) {
  navigation_toolbox.activate(1);
  myroute.set_home(new navigation_point(pos.lon, pos.lat, home_style));

  navigation_update_url();
}

function navigation_add_via(pos) {
  navigation_toolbox.activate(1);
  myroute.add_via(new navigation_point(pos.lon, pos.lat, via_style));

  navigation_update_url();
}

function navigation_set_destination(pos) {
  navigation_toolbox.activate(1);
  myroute.set_destination(new navigation_point(pos.lon, pos.lat, home_style));

  navigation_update_url();
}

function navigation_update_url() {

  location.hash="#navigation="+myroute.home.lon+myroute.home.lat;
}

var anzeige;
function nav_show(route) {
  if(anzeige){
    anzeige.hide();
  }
  anzeige=route;
  route.show();
}

var nav=new navigation_cloudmade();

function calculate_route(){
  if(myroute.home && myroute.destination && (myroute.home.geometry.toString() != myroute.destination.geometry.toString())) {
    nav.get_route({ start_point: myroute.home.geometry, transit_points: myroute.via, end_point: myroute.destination.geometry, route_type: myroute.route_type, route_type_modifier: myroute.route_type_modifier}, nav_show);
  }
}

function navigation_toolboxtext() {
  var utm=new OpenLayers.Projection("EPSG:4326");

  if(!(myroute.home && myroute.destination)) {
    document.getElementById("navigation_starttext").innerHTML = "Select your home and your destination on the map!<br/><br/>";
  } else {
    document.getElementById("navigation_starttext").innerHTML = "";
  }

  if(!myroute.home) {
    document.getElementById("navigation_hometext").innerHTML = "home";
  } else {
    var home = myroute.home.geometry;//.transform(map.getProjectionObject(), utm);
    document.getElementById("navigation_hometext").innerHTML = home.x.toFixed(5) + ", " + home.y.toFixed(5);
  }

  if(myroute.via.length==0) {
    document.getElementById("navigation_viatext").innerHTML = "via";
  } else {
    document.getElementById("navigation_viatext").innerHTML = "";
    for(var i=0; i<myroute.via.length; i++) {
      document.getElementById("navigation_viatext").innerHTML += myroute.via[i].geometry.x.toFixed(5) + ", " + myroute.via[i].geometry.y.toFixed(5);
      if(i!=myroute.via.length-1) {
        document.getElementById("navigation_viatext").innerHTML += "<br/>";
      }
    }
  }

  if(!myroute.destination) {
    document.getElementById("navigation_destinationtext").innerHTML = "destination";
  } else {
    var destination = myroute.destination.geometry;//.transform(map.getProjectionObject(), utm);
    document.getElementById("navigation_destinationtext").innerHTML = destination.x.toFixed(5) + ", " + destination.y.toFixed(5);
  }
}

function navigation_init() {
  navigation_toolbox=new toolbox({
    icon: "plugins/navigation/icon.png",
    icon_title: "navigation",
    weight: -3,
  });
  register_toolbox(navigation_toolbox);

  if(plugins_loaded("contextmenu")) {
    contextmenu_add("plugins/navigation/icon_home.png", lang("navigation:set_home"), navigation_set_home);
    contextmenu_add("plugins/navigation/icon_via.png", lang("navigation:add_via"), navigation_add_via);
    contextmenu_add("plugins/navigation/icon_destination.png", lang("navigation:set_destination"), navigation_set_destination);
  }

  var text = "<i>Navigation</i><br/><br/><div id='navigation_starttext'></div><img src='plugins/navigation/icon_home.png' onclick='alert(home.lon + \"|\" + home.lat)'> <span id='navigation_hometext'></span><br/><img src='plugins/navigation/icon_via.png'> <span id='navigation_viatext' style='display:inline-block; padding-top:7px;'></span><br/><img src='plugins/navigation/icon_destination.png'> <span id='navigation_destinationtext'></span><br/><br/><select id='travelwith' onchange='myroute.change_route_type(this)'><option value='car'>car</option><option value='car (shortest)'>car (shortest)</option><option value='bicycle'>bicycle</option><option value='foot'>foot</option></select> <button onclick='myroute.invert()'>invert</button><button onclick='myroute.remove()'>clear</button><br/><br/>";
  navigation_toolbox.content.innerHTML=text;
  navigation_toolboxtext();
}

function navigation_info(chapters, ob) {
  if(ob.geo_center()) {
    // set home
    var a=document.createElement("a");
    a.onclick=navigation_set_home.bind(this, ob.geo_center());
    dom_create_append_text(a, lang("navigation:set_home"));

    var entry={
      head: 'actions',
      weight: 9,
      content: [ a ]
    };

    chapters.push(entry);

    // set destination
    var a=document.createElement("a");
    a.onclick=navigation_set_destination.bind(this, ob.geo_center());
    dom_create_append_text(a, lang("navigation:set_destination"));

    var entry={
      head: 'actions',
      weight: 9,
      content: [ a ]
    };

    chapters.push(entry);
  }
}

register_hook("init", navigation_init);
register_hook("info", navigation_info);
