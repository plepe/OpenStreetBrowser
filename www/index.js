var map;
var vector_layer;
var showing="";

var display_data;
var redraw_timer;
var view_changed_timer;
var debug_msg;
var shown_features=[];
var showing_details=true;
var loaded_list={};
var view_changed_last;
var location_params={};

var polygon_control;
var permalink_control;

function details_content_submit(event) {
  // Sometimes it happens, that it want to submit to the form. 
  // Just ignore event.
}

function show_msg(msg, debuginfo) {
  hide_msg();
  debug_msg=document.createElement("div");
  debug_msg.className="debugMsg";
  debug_msg.innerHTML=msg+"<br><pre id='debug'>"+debuginfo+"</pre><input type='button' value='Close' onClick='hide_msg()'>\n";
  document.body.appendChild(debug_msg);
}

function hide_msg() {
  if(debug_msg)
    debug_msg.parentNode.removeChild(debug_msg);
}

function go_to(lon, lat, zoom) {
    var lonlat = new OpenLayers.LonLat(lon, lat);
    if(!zoom)
      zoom=map.zoom;
    map.setCenter(lonlat, zoom);
}

function zoom_to_feature() {
  if(vector_layer.features.length==0)
    return;

  var extent=vector_layer.features[0].geometry.getBounds();

  for(var i=1; i<vector_layer.features.length; i++) {
    extent.extend(vector_layer.features[i].geometry.getBounds());
  }

  this.map.zoomToExtent(extent);
}

function hide() {
  //var map_key=document.getElementById("map_key");
  var details=document.getElementById("details");
  var map=document.getElementById("map");

  details.className="info_hidden";
//  map.className="map";
}

function hide_features() {
  vector_layer.removeFeatures(shown_features);
  shown_features=[];
  call_hooks("hide_features");
}

function get_hash() {
  if(location_params.obj)
    return location_params.obj;
  return "";
}

function call_back(response) {
  var data=response.responseXML;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var info_content=document.getElementById("details_content");
  var map_div=document.getElementById("map");
  var info=document.getElementById("details");
  showing=get_hash();

  info.className="info";
//  map_div.className="map";
  var text_node=data.getElementsByTagName("text");
  if(text_node) {
    if(!text_node[0])
      show_msg("Returned data invalid", response.responseText);
    var div=document.createElement("div");
    div.innerHTML=get_content(text_node[0]);

    while(info_content.firstChild)
      info_content.removeChild(info_content.firstChild);
    info_content.appendChild(div);
  }

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);

  var zoom=data.getElementsByTagName("zoom");
  if(zoom.length>0)
    zoom=zoom[0];
  else
    delete(zoom);

  var x=get_hash();
  x=get_loaded_object(x);

  if(x) {
    x.display();
  }

  if(zoom) {
    pan_to_highlight(zoom.getAttribute("lon"),
                     zoom.getAttribute("lat"),
		     zoom.getAttribute("zoom"));
  }

  check_overlays(data);
  call_hooks("load_details", data, x);
  showing_details=true;

  return;
}

function redraw() {
  hide_features();
  unset_highlight();
  var x=get_hash();
  showing_details=false;
  call_hooks("unselect_all");

  if(x=="") {
    show_list();
    list_reload();
  }
  else if(x=="mapkey") {
    hide();
    //var info=document.getElementById("map_key");
    var map_div=document.getElementById("map");

    info.className="info";
//    map_div.className="map";
  }
  else if(x.substr(0, 7)=="search_") {
    first_load=0;
    real_search(x.substr(7));
  }
  else {
    var param={"obj": x};
    call_hooks("request_details", param);
    ajax("details", param, call_back);

    var details_content=document.getElementById("details_content");
    var details=document.getElementById("details");
//    var map_div=document.getElementById("map");

    details_content.innerHTML="<div class=\"loading\"><img src=\"img/ajax_loader.gif\" /> "+t("loading")+"</div>";
    //map_key.className="info_hidden";
    details.className="info_loading";
  }

  if(location_params.lat&&location_params.lon) {
    var lonlat = new OpenLayers.LonLat(location_params.lon, location_params.lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    if(location_params.zoom)
      map.setCenter(lonlat, location_params.zoom);
    else
      map.setCenter(lonlat);
  }
  else if(location_params.zoom) {
    map.zoomTo(location_params.zoom);
  }
}

// set_location ... resolve a link with all parts, moves accordingly 
//                    and maybe loads additional data
// PARAMETERS:
//   params     ... a hash (or its string-representation) describing the 
//                  current view (as you can get from the function 
//                  get_permalink() )
// RETURN:
//   nothing
function set_location(params) {
  if(typeof(params)=="string")
    params=string_to_hash(params);

  location_params=params;

  if(map) {
    redraw();
  }
  else {
    start_lon=params.lon;
    start_lat=params.lat;
    start_zoom=params.zoom;
  }
}

var last_location_hash;
function check_redraw() {
  location_params={};

  if(location.hash!=last_location_hash) {
    if(location.hash.substr(0, 2)=="#?") {
      call_hooks("recv_permalink", location.hash.substr(2));
      location_params=string_to_hash(location.hash.substr(2));
    }
    else if(location.hash.substr(0, 1)=="#") {
      location_params.obj=location.hash.substr(1);
    }

    call_hooks("hash_changed", location_params);
    last_location_hash=location.hash;
  }

  redraw_timer=setTimeout("check_redraw()", 300);
}

function view_changed_start(event) {
  if((map.zoom)&&(start_zoom!=map.zoom))
    first_load=0;

  var map_div=document.getElementById("map");
  map_div.style.cursor="default";

  if(view_changed_timer)
    clearTimeout(view_changed_timer);

  call_hooks("view_changed_start", event);
}

function view_changed_delay() {
  if(get_hash()=="")
    list_reload();
}

function view_changed(event) {
  if(view_changed_timer)
    clearTimeout(view_changed_timer);

  view_changed_last=new Date().getTime();

  view_changed_timer=setTimeout("view_changed_delay()", 300);
  check_mapkey();

  call_hooks("view_changed", event);
}

// get_permalink ... returns current view as hash array
// PARAMETERS:
//   none
// RETURNS:
//   a hash array describing the current view
function get_permalink() {
  var center=map.getCenter().transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
  var permalink = { zoom: map.zoom, lat: center.lat, lon: center.lon };
  if(location_params.obj)
    permalink.obj=location_params.obj;

  call_hooks("get_permalink", permalink);

  return permalink;
}

// update_permalink ... forces an update of the permalink
function update_permalink() {
  permalink_control.updateLink();
}

function get_baseurl() {
  return location.protocol+"//"+location.hostname+location.pathname;
}

function init() {
  show_list();

  if(!location.hash) {
    location.hash="#";
  }
//  else if(location.hash=="#")

  map = new OpenLayers.Map("map",
	  { maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
	    numZoomLevels: 19,
	    maxResolution: 156543.0399,
	    units: 'm',
	    projection: new OpenLayers.Projection("EPSG:900913"),
	    displayProjection: new OpenLayers.Projection("EPSG:4326"),
	    controls: [ new OpenLayers.Control.PanZoomBar(),
			new OpenLayers.Control.LayerSwitcher(),
			new OpenLayers.Control.Navigation() ]
	  });

  call_hooks("basemap_init");

  layerHill = new OpenLayers.Layer.OSM(
    "Hillshading (NASA SRTM3 v2)",
    "http://toolserver.org/~cmarqu/hill/",
    { type: 'png',
    displayOutsideMaxExtent: true, isBaseLayer: false,
    transparent: true, "visibility": false });
  map.addLayers([ layerHill ]);

  layerContour = new OpenLayers.Layer.OSM(
    "Contourshading",
    "http://hills-nc.openstreetmap.de/",
    { type: 'png', numZoomLevels: 16,
    displayOutsideMaxExtent: true, isBaseLayer: false,
    transparent: true, "visibility": false });
  map.addLayers([ layerContour ]); 

  map.div.oncontextmenu = function noContextMenu(e) {
    rightclick(e);
    return false; //cancel the right click of brower
  };

  var permalink=document.getElementById("permalink");
  permalink_control=new OpenLayers.Control.Permalink(permalink, get_baseurl()+"#");
  map.addControl(permalink_control);
  permalink_control.createParams=get_permalink;

  map.addControl(new OpenLayers.Control.MousePosition());
  map.addControl(new OpenLayers.Control.ScaleLine());

  if(start_lon&&(first_load)) {
    var lonlat = new OpenLayers.LonLat(start_lon, start_lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    map.setCenter(lonlat, start_zoom);
  }

  redraw_timer=setTimeout("check_redraw()", 300);
  register_hook("hash_changed", redraw);

  map.events.register("moveend", map, view_changed);
  map.events.register("movestart", map, view_changed_start);
  map.events.register("click", map, view_click);

  overlays_init();
  map_key_init();

  call_hooks("init");
  //setTimeout("call_hooks(\"post_init\")", 2000);
}


function add_funs(arr) {
  arr.search=function(needle) {
    for(var i=0; i<this.length; i++)
      if(this[i]==needle)
	return i;
    return null;
  }
  arr.del=function(needle) {
    var i=this.search(needle);
    var new_arr=[];
    for(var i=0; i<this.length; i++)
      if(this[i]!=needle)
        new_arr.push(this[i]);
    add_funs(new_arr);
    return new_arr;
  }
}

//add_funs(info_lists);
window.onload=init;

