/* known bugs:
 * - by clicking on "clear" button last element of via not removed
 * - inverting route gives out errors
 * - home and destination icons are not yet changed when inverting the route
 * - drag and drop of the icons doesn't work yet
 */

var navigation_toolbox;
var navigation_current_route=new navigation_route();

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
  this.type="navigation_point";

  // id
  this.id=function() {
    return this.lat.toFixed(5)+","+this.lon.toFixed(5);
  }

  // name
  this.name=function() {
    return this.lat.toFixed(5)+", "+this.lon.toFixed(5);
  }

  // geo_center
  this.geo_center=function() {
    return this.feature;
  }

  // geometry
  this.geometry=function() {
    return this.feature.geometry;
  }

  // hide
  this.hide=function() {
    drag_layer.unselect(this.feature);
    drag_layer.removeFeatures([this.feature]);
  }

  // show
  this.show=function() {
    drag_layer.removeFeatures([this.feature]);
    drag_layer.addFeatures([this.feature]);
  }

  // remove
  this.remove=function() {
    this.hide();
  }

  // finish_drag
  this.finish_drag=function(pos) {
    var lonlat=pos.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

    if(lonlat.x) {
      this.lon=lonlat.x;
      this.lat=lonlat.y;
    }

    call_hooks("geo_object_change", null, this);
  }

  // constructor
  this.lon=parseFloat(lon);
  this.lat=parseFloat(lat);
  //this.id="";

  var pos = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo = new OpenLayers.Geometry.Point(pos.lon, pos.lat);
  this.feature = new OpenLayers.Feature.Vector(geo, 0, style);
  this.feature.ob=this;
}

function navigation_route(id) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="navigation_route";

  // name
  this.name=function() {
    return lang("navigation:route_name");
  }

  // id
  this.id=function() {
    var param=[];

    if(!(navigation_current_route.home && navigation_current_route.destination)) {
      return;
    }

    param.push(navigation_current_route.travel_with);
    param.push(navigation_current_route.home.id());
    for(var i=0; i<navigation_current_route.via.length; i++) {
      param.push(navigation_current_route.via[i].id());
    }
    param.push(navigation_current_route.destination.id());

    return param.join(":");
  }

  // geo
  this.geo=function() {
    if(!this.calculated_route)
      return;

    return this.calculated_route.geo();
  }

  // info
  this.info=function(chapters) {
    this.calculate_route();

    this.instructions=document.createElement("div");
    this.instructions.innerHTML="<img src=\"img/ajax_loader.gif\" /> "+lang("loading");

    chapters.push({
      head: lang("navigation:instructions"),
      content: this.instructions,
      weight: 1
    });
  }

  //changes route type
  this.change_route_type=function() {
    var select=document.getElementById("navigation_travel_with");
    this.travel_with=select.value;

    navigation_update_url();
  }

  //inverts route
  this.invert=function(button){
    var temp=this.home;
    this.home=this.destination;
    this.destination=temp;
    this.via.reverse();

    navigation_toolboxtext();
    navigation_update_url();
  }

  this.show=function(){
    for(var i=0; i<this.members.length; i++) {
      this.members[i].show();
    }
  }

  this.hide=function(){
    for(var i=0; i<this.members.length; i++) {
      this.members[i].hide();
    }
  }

  this.info_show=function() {
    this.show();
  }

  this.info_hide=function() {
    this.hide();
  }

  /*
  this.finish_drag=function(pos){
    alert("fertig");
  }
  
  this.next_drag=function(pos){
    alert("test");

  */

  //removes the route
  this.remove=function() {
    if(this.home)
      this.home.remove();
    if(this.destination)
      this.destination.remove();
    for(var i=0;i<this.via.length;i++){
      this.via[i].remove(i);
    }
    if(this.calculated_route)
      this.calculated_route.remove();
    unregister_hooks_object(this);
  }

  // update
  this.update=function() {
    this.members=[];
    this.member_roles=[];

    if(this.calculated_route) {
      this.members.push(this.calculated_route);
      this.member_roles.push("route");
    }
    if(this.home) {
      this.members.push(this.home);
      this.member_roles.push("home");
    }
    for(var i=0; i<this.via.length; i++) {
      this.members.push(this.via[i]);
      this.member_roles.push("via");
    }
    if(this.destination) {
      this.members.push(this.destination);
      this.member_roles.push("destination");
    }

    navigation_toolboxtext();
  }

  //sets your home point
  this.set_home=function(point) {
    if(this.home) {
      this.home.remove();
    }
    this.home=point;

    this.update();
  }

  //sets your destination point
  this.set_destination=function(point) {
    if(this.destination) {
      this.destination.remove();
    }
    this.destination=point;

    this.update();
  }

  //adds a via point to the via array
  this.add_via=function(point) {
    this.via.push(point);

    this.update();
  }

 
  //removes the i-th point from the via array
  this.remove_via=function(i){
    this.via[i].remove();
    this.via.splice(i, 1);
  }

  // show_route
  this.show_route=function(route) {
    if(this.calculated_route)
      this.calculated_route.hide();

    this.calculated_route=route;

    if(this.instructions) {
      dom_clean(this.instructions);

      this.calculated_route.print_instructions(this.instructions);
    }

    this.update();
    this.show();

    this.geo_zoom_to();
  }

  // calculate_route
  this.calculate_route=function() {
    if(!(this.home && this.destination))
      return;

    var param={};

    param.start_point=this.home.geometry();
    if(this.via.length) {
      param.transit_points=[];
      for(var i=0; i<this.via.length; i++)
	param.transit_points.push(this.via[i].geometry());
    }
    param.end_point=this.destination.geometry();
    param.travel_with=this.travel_with;

    nav.get_route(param, this.show_route.bind(this));
  }

  // notify_change
  this.notify_change=function(dummy, ob) {
    var affected=false;
    if(this.home==ob)
      affected=true;
    if(this.destination==ob)
      affected=true;
    for(var i=0; i<this.via.length; i++)
      if(this.via[i]==ob)
	affected=true;
    if(!affected)
      return;
    
    navigation_toolboxtext();
    navigation_update_url();
  }

  // constructor
  this.via=new Array();
  this.travel_with=navigation_cloudmade_travelwith[0].id;

  if(id) {
    id=id.split(":");

    this.travel_with=id[0];
    var latlon=id[1].split(",");
    this.set_home(new navigation_point(latlon[1], latlon[0], home_style));
    for(var i=2; i<id.length-1; i++) {
      var latlon=id[i].split(",");
      this.add_via(new navigation_point(latlon[1], latlon[0], via_style));
    }
    var latlon=id[i].split(",");
    this.set_destination(new navigation_point(latlon[1], latlon[0], destination_style));
  }

  register_hook("geo_object_change", this.notify_change.bind(this), this);
}

function navigation_set_home(pos) {
  navigation_toolbox.activate(1);
  navigation_current_route.set_home(new navigation_point(pos.lon, pos.lat, home_style));

  navigation_update_url();
}

function navigation_add_via(pos) {
  navigation_toolbox.activate(1);
  navigation_current_route.add_via(new navigation_point(pos.lon, pos.lat, via_style));

  navigation_update_url();
}

function navigation_set_destination(pos) {
  navigation_toolbox.activate(1);
  navigation_current_route.set_destination(new navigation_point(pos.lon, pos.lat, destination_style));

  navigation_update_url();
}

function navigation_update_url() {
  var id=navigation_current_route.id();

  if(!id)
    return;

  location.hash="#navigation="+id;
}

var nav=new navigation_cloudmade();

function navigation_toolboxtext() {
  var utm=new OpenLayers.Projection("EPSG:4326");

  var starttext=document.getElementById("navigation_starttext");
  dom_clean(starttext);
  if(!(navigation_current_route.home && navigation_current_route.destination)) {
    dom_create_append_text(starttext, lang("navigation:toolbox_help"));
  }

  var nav_table=document.getElementById("navigation_points");
  dom_clean(nav_table);

  // home
  var tr=dom_create_append(nav_table, "tr");

  var td=dom_create_append(tr, "td");
  var img=dom_create_append(td, "img");
  img.src="plugins/navigation/icon_home.png";

  var td=dom_create_append(tr, "td");
  if(!navigation_current_route.home) {
    dom_create_append_text(td, lang("navigation:home"));
  }
  else {
    dom_create_append_text(td, navigation_current_route.home.name());
  }

  // via
  var tr=dom_create_append(nav_table, "tr");

  var td=dom_create_append(tr, "td");
  var img=dom_create_append(td, "img");
  img.src="plugins/navigation/icon_via.png";

  var td=dom_create_append(tr, "td");
  if(navigation_current_route.via.length==0) {
    var div=dom_create_append(td, "div");
    dom_create_append_text(div, lang("navigation:via"));
  }
  else {
    for(var i=0; i<navigation_current_route.via.length; i++) {
      var div=dom_create_append(td, "div");
      dom_create_append_text(div, navigation_current_route.via[i].name());
    }
  }

  // destination
  var tr=dom_create_append(nav_table, "tr");

  var td=dom_create_append(tr, "td");
  var img=dom_create_append(td, "img");
  img.src="plugins/navigation/icon_destination.png";

  var td=dom_create_append(tr, "td");
  if(!navigation_current_route.destination) {
    dom_create_append_text(td, lang("navigation:destination"));
  }
  else {
    dom_create_append_text(td, navigation_current_route.destination.name());
  }

  // travel with
  var select=document.getElementById("navigation_travel_with");
  for(var i=0; i<select.options.length; i++) {
    var option=select.options[i];
    option.selected=(option.value==navigation_current_route.travel_with);
  }
}

function navigation_current_route_remove() {
  navigation_current_route.remove();
}

function navigation_current_route_change_route_type() {
  navigation_current_route.change_route_type();
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

  this.toolbox_content=dom_create_append(navigation_toolbox.content, "div");
  this.toolbox_content.className="navigation";

  var title=dom_create_append(this.toolbox_content, "h1");
  dom_create_append_text(title, lang("navigation:toolbox_title"));

  var help=dom_create_append(this.toolbox_content, "div");
  help.id="navigation_starttext";

  var table=dom_create_append(this.toolbox_content, "table");
  table.id="navigation_points";

  var select=dom_create_append(this.toolbox_content, "select");
  select.onchange=navigation_current_route_change_route_type;
  select.id="navigation_travel_with";

  for(var i=0; i<navigation_cloudmade_travelwith.length; i++) {
    var option=dom_create_append(select, "option");
    option.value=navigation_cloudmade_travelwith[i].id;
    dom_create_append_text(option, lang("navigation_cloudmade:"+navigation_cloudmade_travelwith[i].id));
  }

  var button=dom_create_append(this.toolbox_content, "button");
  button.onclick=navigation_current_route.invert.bind(navigation_current_route, button);
  dom_create_append_text(button, lang("navigation:invert"));

  var button=dom_create_append(this.toolbox_content, "button");
  button.onclick=navigation_current_route_remove;//.bind(navigation_current_route, button);
  dom_create_append_text(button, lang("navigation:remove"));

  //var text = "<img src='plugins/navigation/icon_home.png' onclick='alert(home.lon + \"|\" + home.lat)'> <span id='navigation_hometext'></span><br/><img src='plugins/navigation/icon_via.png'> <span id='navigation_viatext' style='display:inline-block; padding-top:7px;'></span><br/><img src='plugins/navigation/icon_destination.png'> <span id='navigation_destinationtext'></span><br/><br/>

  navigation_toolboxtext();
}

function navigation_search_object(ret, id) {
  var m;
  if(m=id.match("^navigation=(.*)$")) {
    if(navigation_current_route.id()!=m[1]) {
      navigation_current_route.remove();
      navigation_current_route=new navigation_route(m[1]);

      navigation_toolboxtext();
    }

    navigation_toolbox.activate(1);

    ret.push(navigation_current_route);
  }
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
register_hook("search_object", navigation_search_object);
