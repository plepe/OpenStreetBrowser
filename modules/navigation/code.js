/* known bugs:
 * - by clicking on "clear" button last element of via not removed
 * - inverting route gives out errors
 * - home and destination icons are not yet changed when inverting the route
 * - drag and drop of the icons doesn't work yet
 */

var navigation_toolbox;
var navigation_current_route=new navigation_route();

var home_style={
    externalGraphic: modulekit_file("navigation", "home.png"),
    graphicWidth: 26,
    graphicHeight: 22,
    graphicXOffset: -13,
    graphicYOffset: -22
  };
var via_style={
    externalGraphic: modulekit_file("navigation", "via.png"),
    graphicWidth: 25,
    graphicHeight: 22,
    graphicXOffset: -16,
    graphicYOffset: -22
  };
var destination_style={
    externalGraphic: modulekit_file("navigation", "destination.png"),
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

  // set_style
  this.set_style=function(style) {
    this.feature.style=style;
  }

  // hide
  this.hide=function() {
    // already removed?
    if(!this.feature.layer)
      return;

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
    return lang("navigation:name");
  }

  // id
  this.id=function() {
    var param=[];

    if(!(this.home && this.destination)) {
      return;
    }

    param.push(this.travel_with);
    param.push(this.home.id());
    for(var i=0; i<this.via.length; i++) {
      param.push(this.via[i].id());
    }
    param.push(this.destination.id());

    return "navigation="+param.join(":");
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

    this.key_data=document.createElement("div");
    this.key_data.innerHTML="<img src=\"img/ajax_loader.gif\" /> "+lang("loading");

    chapters.push({
      head: "key_data",
      content: this.key_data,
      weight: 1
    });

    this.instructions=document.createElement("div");
    this.instructions.innerHTML="<img src=\"img/ajax_loader.gif\" /> "+lang("loading");

    chapters.push({
      head: "instructions",
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

  // reverse
  this.reverse=function(button){
    var temp=this.home;
    this.home=this.destination;
    this.destination=temp;
    this.via.reverse();

    this.update();
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
      this.home.set_style(home_style);
      this.members.push(this.home);
      this.member_roles.push("home");
    }
    for(var i=0; i<this.via.length; i++) {
      this.via[i].set_style(via_style);
      this.members.push(this.via[i]);
      this.member_roles.push("via");
    }
    if(this.destination) {
      this.destination.set_style(destination_style);
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
    point.show();

    this.update();
  }

  //sets your destination point
  this.set_destination=function(point) {
    if(this.destination) {
      this.destination.remove();
    }
    this.destination=point;
    point.show();

    this.update();
  }

  //adds a via point to the via array
  this.add_via=function(point) {
    this.via.push(point);
    point.show();

    this.update();
  }

 
  //removes the i-th point from the via array
  this.remove_via=function(i){
    this.via[i].remove();
    this.via.splice(i, 1);
  }

  //remove a navigation point
  this.remove_points=function(pos) {
    var points=[];
    if(this.home)
      points.push(this.home);
    for(var i=0; i<this.via.length; i++)
      points.push(this.via[i]);
    if(this.destination)
      points.push(this.destination);

    switch(pos) {
      case "home":
	this.home.remove();
	if(points.length<=2) {
	  this.home=null;
	  this.via=[];
	}
	else {
	  this.home=points[1];
	  this.via=points.slice(2, -1);
	  this.destination=points[points.length-1];
	}
	break;
      case "destination":
	this.destination.remove();
	if(points.length<=2) {
	  this.via=[];
	  this.destination=null;
	}
	else {
	  this.via=points.slice(1, -2);
	  this.destination=points[points.length-2];
        }
	break;
      default:
	this.via[pos].remove();
	if(points.length<=2) {
	  // this shouldn't happen, but you never know
	  this.via=[];
	}
	else {
	  this.via=points.slice(1, pos+1).concat(points.slice(pos+2, -1));
	  this.destination=points[points.length-1];
	}
	break;
    }

    if((!this.home)||(!this.destination))
      this.calculated_route.remove();
    this.update();
    navigation_update_url();
  }

  //remove a navigation point
  this.exchange_points=function(pos) {
    var points=[];
    if(this.home)
      points.push(this.home);
    for(var i=0; i<this.via.length; i++)
      points.push(this.via[i]);
    if(this.destination)
      points.push(this.destination);

    switch(pos) {
      case "home":
	var p;
	p=points[0];
        points[0]=points[1];
	points[1]=p;
        break;
      default:
	var p;
	p=points[pos+1];
        points[pos+1]=points[pos+2];
	points[pos+2]=p;
    }

    this.home=points[0];
    this.via=[];
    for(var i=1; i<points.length-1; i++)
      this.via.push(points[i]);
    this.destination=points[points.length-1];

    this.update();
    navigation_update_url();
  }

  // show_route
  this.show_route=function(route) {
    if(this.calculated_route)
      this.calculated_route.hide();

    this.calculated_route=route;

    if(this.key_data) {
      dom_clean(this.key_data);

      this.calculated_route.print_key_data(this.key_data);
    }

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
    m=id.match(/^navigation=(.*)$/);
    params=m[1].split(":");

    this.travel_with=params[0];
    var latlon=params[1].split(",");
    this.set_home(new navigation_point(latlon[1], latlon[0], home_style));
    for(var i=2; i<params.length-1; i++) {
      var latlon=params[i].split(",");
      this.add_via(new navigation_point(latlon[1], latlon[0], via_style));
    }
    var latlon=params[i].split(",");
    this.set_destination(new navigation_point(latlon[1], latlon[0], destination_style));
  }

  register_hook("geo_object_change", this.notify_change.bind(this), this);
}

function navigation_set_home(pos) {
  navigation_toolbox.activate(1);

  if(!pos.lon)
    pos=pos.lonlat();
  navigation_current_route.set_home(new navigation_point(pos.lon, pos.lat, home_style));

  navigation_update_url();
}

function navigation_add_via(pos) {
  navigation_toolbox.activate(1);

  if(!pos.lon)
    pos=pos.lonlat();
  navigation_current_route.add_via(new navigation_point(pos.lon, pos.lat, via_style));

  navigation_update_url();
}

function navigation_set_destination(pos) {
  navigation_toolbox.activate(1);

  if(!pos.lon)
    pos=pos.lonlat();
  navigation_current_route.set_destination(new navigation_point(pos.lon, pos.lat, destination_style));

  navigation_update_url();
}

function navigation_update_url() {
  var id=navigation_current_route.id();

  if(!id)
    return;

  set_url({ obj: id });
}

var nav=new navigation_cloudmade();

function navigation_remove_points(pos) {
  navigation_current_route.remove_points(pos);
}

function navigation_exchange_points(pos) {
  navigation_current_route.exchange_points(pos);
}

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
  td.className="icon";
  td.rowSpan=2;
  var img=dom_create_append(td, "img");
  img.src=modulekit_file("navigation", "icon_home.png");

  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  if(!navigation_current_route.home) {
    dom_create_append_text(td, lang("navigation:home"));
  }
  else {
    dom_create_append_text(td, navigation_current_route.home.name());
  }

  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  td.className="remove";

  var img=dom_create_append(td, "img");
  img.src=modulekit_file("navigation", "remove.png");
  img.onclick=navigation_remove_points.bind(this, "home");

  var td=dom_create_append(tr, "td");
  td.className="exchange_placeholder";

  var tr=dom_create_append(nav_table, "tr");
  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  td.className="exchange";

  var img=dom_create_append(td, "img");
  img.src=modulekit_file("navigation", "exchange.png");
  img.onclick=navigation_exchange_points.bind(this, "home");

  // via
  if(navigation_current_route.via.length) {
    for(var i=0; i<navigation_current_route.via.length; i++) {
      var tr=dom_create_append(nav_table, "tr");

      var td=dom_create_append(tr, "td");
      td.className="icon";
      td.rowSpan=2;
      var img=dom_create_append(td, "img");
      img.src=modulekit_file("navigation", "icon_via.png");

      var td=dom_create_append(tr, "td");
      td.rowSpan=2;
      var div=dom_create_append(td, "div");
      dom_create_append_text(div, navigation_current_route.via[i].name());

      var td=dom_create_append(tr, "td");
      td.rowSpan=2;
      td.className="remove";

      var img=dom_create_append(td, "img");
      img.src=modulekit_file("navigation", "remove.png");
      img.onclick=navigation_remove_points.bind(this, i);

      var tr=dom_create_append(nav_table, "tr");
      var td=dom_create_append(tr, "td");
      td.rowSpan=2;
      td.className="exchange";

      var img=dom_create_append(td, "img");
      img.src=modulekit_file("navigation", "exchange.png");
      img.onclick=navigation_exchange_points.bind(this, i);
    }
  }

  // destination
  var tr=dom_create_append(nav_table, "tr");

  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  td.className="icon";
  var img=dom_create_append(td, "img");
  img.src=modulekit_file("navigation", "icon_destination.png");

  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  if(!navigation_current_route.destination) {
    dom_create_append_text(td, lang("navigation:destination"));
  }
  else {
    dom_create_append_text(td, navigation_current_route.destination.name());
  }

  var td=dom_create_append(tr, "td");
  td.rowSpan=2;
  td.className="remove";

  var img=dom_create_append(td, "img");
  img.src=modulekit_file("navigation", "remove.png");
  img.onclick=navigation_remove_points.bind(this, "destination");

  var tr=dom_create_append(nav_table, "tr");
  var td=dom_create_append(tr, "td");
  td.className="exchange_placeholder";

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

function navigation_current_route_reverse() {
  navigation_current_route.reverse();
}

function navigation_current_route_go() {
  if(navigation_current_route)
    set_url({ obj: navigation_current_route.id() });
}

function navigation_init() {
  navigation_toolbox=new toolbox({
    icon: modulekit_file("navigation", "icon.png"),
    icon_title: lang("navigation:name"),
    weight: -3,
  });
  register_toolbox(navigation_toolbox);

  if(modulekit_loaded("contextmenu")) {
    contextmenu_add(modulekit_file("navigation", "icon_home.png"), lang("navigation:set_home"), navigation_set_home);
    contextmenu_add(modulekit_file("navigation", "icon_via.png"), lang("navigation:add_via"), navigation_add_via);
    contextmenu_add(modulekit_file("navigation", "icon_destination.png"), lang("navigation:set_destination"), navigation_set_destination);
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
  button.onclick=navigation_current_route_reverse;
  dom_create_append_text(button, lang("navigation:reverse"));

  var button=dom_create_append(this.toolbox_content, "button");
  button.onclick=navigation_current_route_go;
  dom_create_append_text(button, lang("navigation:go"));

  //var text = "<img src='plugins/navigation/icon_home.png' onclick='alert(home.lon + \"|\" + home.lat)'> <span id='navigation_hometext'></span><br/><img src='plugins/navigation/icon_via.png'> <span id='navigation_viatext' style='display:inline-block; padding-top:7px;'></span><br/><img src='plugins/navigation/icon_destination.png'> <span id='navigation_destinationtext'></span><br/><br/>

  navigation_toolboxtext();
}

function navigation_search_object(ret, id) {
  var m;
  if(m=id.match("^navigation=(.*)$")) {
    if(navigation_current_route.id()!=id) {
      navigation_current_route.remove();
      navigation_current_route=new navigation_route(id);

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
    a.onclick=navigation_set_home.bind(this, ob);
    dom_create_append_text(a, lang("navigation:set_home"));

    var entry={
      head: 'actions',
      weight: 9,
      content: [ a ]
    };

    chapters.push(entry);

    // set destination
    var a=document.createElement("a");
    a.onclick=navigation_set_destination.bind(this, ob);
    dom_create_append_text(a, lang("navigation:set_destination"));

    var entry={
      head: 'actions',
      weight: 9,
      content: [ a ]
    };

    chapters.push(entry);

    // add via
    var a=document.createElement("a");
    a.onclick=navigation_add_via.bind(this, ob);
    dom_create_append_text(a, lang("navigation:add_via"));

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
