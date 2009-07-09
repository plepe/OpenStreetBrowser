var style_default;
var style_tunnel;
var map;
var vector_layer;
var showing="";

var display_data;
var redraw_timer;
var view_changed_timer;
var highlight_feature;
var highlight_feature_timer;

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

function pan_to_highlight(lon, lat) {
  map.panTo(new OpenLayers.LonLat(lon, lat));
}

function set_highlight(list) {
  highlight_feature=[];
  var lon=0, lat=0;

  if(highlight_feature_timer)
    clearTimeout(highlight_feature_timer);

  for(var i=0; i<list.length; i++) {
    var el=get_loaded_element(list[i]);
    var p=new OpenLayers.Geometry.Point(el.data.getAttribute("lon"), el.data.getAttribute("lat"));
    lon+=Number(el.data.getAttribute("lon"));
    lat+=Number(el.data.getAttribute("lat"));

    highlight_feature.push(new OpenLayers.Feature.Vector(p, 0, {
      externalGraphic: "ring.png",
      graphicWidth: 25,
      graphicHeight: 25,
      graphicXOffset: -13,
      graphicYOffset: -13,
      graphicOpacity: 1,
      fill: "none"
    }));
  }

  vector_layer.addFeatures(highlight_feature);

  lon=lon/list.length;
  lat=lat/list.length;
  highlight_feature_timer=setTimeout("pan_to_highlight("+lon+", "+lat+")", 500);
}

function unset_hightlight() {
  if(highlight_feature_timer)
    clearTimeout(highlight_feature_timer);

  vector_layer.removeFeatures(highlight_feature);
  highlight_feature=0;
}

function hide() {
  var map_key=document.getElementById("map_key");
  var details=document.getElementById("details");
  var map=document.getElementById("map");

  map_key.className="info_hidden";
  details.className="info_hidden";
  map.className="map";
}

function search_focus(ob) {
  ob.value="";
}

function search(ob) {
  alert(ob.value);
// TODO
}

function hide_features() {
  vector_layer.removeFeatures(vector_layer.features);
}

function get_hash() {
  return location.hash.substr(1);
}

function call_back(data) {
  if(!data) {
    //alert("no data");
    return;
  }

  var info_content=document.getElementById("details_content");
  var map_div=document.getElementById("map");
  var info=document.getElementById("details");
  showing=get_hash();

  info.className="info";
  map_div.className="map_with_info";
  var text_node=data.getElementsByTagName("text");
  if(text_node) {
    info_content.innerHTML=text_node[0].textContent;
  }

  var osm=data.getElementsByTagName("osm");
  load_elements_from_xml(osm);

  var x=get_hash();
  x=get_loaded_element(x);

  if(x) {
    x.display();

    if(first_load) {
      setTimeout("zoom_to_feature(\""+x.long_id+"\")", 200);
      first_load=0;
    }
  }

  return;

  display_data=data;

  vector_layer.removeFeatures(vector_layer.features);

  if(data[2]) {
    var features=new Array();

    for(var i in data[2]) {
      var d=data[2][i];

      if(d.type=="icon") {
	var p=new OpenLayers.Geometry.Point(d.lon, d.lat);
	switch(d.icon) {
	  case "ring":
	    features.push(new OpenLayers.Feature.Vector(p, 0, {
	      externalGraphic: "ring.png",
	      graphicWidth: 25,
	      graphicHeight: 25,
	      graphicXOffset: -13,
	      graphicYOffset: -13,
	      graphicOpacity: 1
	    }));
	    break;
	  case "hst":
	    features.push(new OpenLayers.Feature.Vector(p, 0, {
	      externalGraphic: "hst.png",
	      graphicWidth: 7,
	      graphicHeight: 7,
	      graphicXOffset: -4,
	      graphicYOffset: -4,
	      graphicOpacity: 1
	    }));
	    break;
	}
      }
      else if(d.type=="linestring") {
	var p=[];
	for(var j in d.line)
	  p.push(new OpenLayers.Geometry.Point(d.line[j].lon, d.line[j].lat));
        var linestring=new OpenLayers.Geometry.LineString(p)
        var vector=new OpenLayers.Feature.Vector(linestring, 0, {
	  strokeWidth: 4,
	  strokeColor: "black",
	  strokeOpacity: 0.7
	})

	features.push(vector);
      }
    }

    vector_layer.addFeatures(features);
  }

  if(get_hash()=="route") {
    //if(display_data[2][280171726]) {
      var lat=5952942.92746939; //display_data[2][280171726].lat;
      var lon=1718481.67040048; //display_data[2][280171726].lon;

      var p1=new OpenLayers.Vector.Point(new OpenLayers.LonLat(15.30, 48.00));
      var p2=new OpenLayers.Vector.Point(new OpenLayers.LonLat(15.45, 47.06));

      vector_layer.addFeature(p1);
      vector_layer.addFeature(p1);

      var line = new OpenLayers.Vector.Line(p1,p4);
      line.setStyle(new OpenLayers.Vector.CanvasStyle({'strokeStyle':'blue', 'lineWidth': 2, 'globalAlpha': .5})); 

      layer.addFeature(line);

      vector_layer.addFeature(p1);
    //}
  }
}

function list_routes() {
  var x=map.calculateBounds();
  ajax("list_routes", { "left": x.left, "top": x.top, "right": x.right, "bottom": x.bottom, "zoom": map.zoom }, call_back);

  var info_content=document.getElementById("details_content");
  var map_div=document.getElementById("map");
  var info=document.getElementById("details");

  info.className="info_loading";
  map_div.className="map_with_info";
  if(showing!="list_routes")
    info_content.innerHTML="Loading ...";
}

function redraw() {
  hide_features();
  var x=get_hash();

  if(x=="") {
    hide();
  }
  else if(x=="mapkey") {
    hide();
    var info=document.getElementById("map_key");
    var map=document.getElementById("map");

    info.className="info";
    map.className="map_with_info";
  }
  else if(x=="list_routes") {
    list_routes();
  }
  else {
    var param={"obj": x};
    if(!get_loaded_element(x))
      param["load"]=1;
    ajax("details", param, call_back);

    var details_content=document.getElementById("details_content");
    var details=document.getElementById("details");
    var map=document.getElementById("map");

    details_content.innerHTML="Loading ...";
    map_key.className="info_hidden";
    details.className="info_loading";
  }
}

var last_location_hash;
function check_redraw() {
  if(location.hash!=last_location_hash) {
    last_location_hash=location.hash;
    redraw();
  }

  redraw_timer=setTimeout("check_redraw()", 300);
}

function view_changed_start() {
  if(view_changed_timer)
    clearTimeout(view_changed_timer);
}

function view_changed_delay() {
  if(get_hash()=="list_routes")
    list_routes();
}

function view_changed() {
  if(view_changed_timer)
    clearTimeout(view_changed_timer);
  view_changed_timer=setTimeout("view_changed_delay()", 500);
}

function init() {
  map = new OpenLayers.Map("map",
	  { maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
	    numZoomLevels: 19,
	    maxResolution: 156543.0399,
	    units: 'm',
	    projection: new OpenLayers.Projection("EPSG:900913"),
	    displayProjection: new OpenLayers.Projection("EPSG:4326")
	  });

  var layerpubtran = new OpenLayers.Layer.OSM("Public Transport", "http://pitr.cg.tuwien.ac.at/tiles/", {numZoomLevels: 17});
  var layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Standard (Mapnik)");
  var layerTah = new OpenLayers.Layer.OSM.Osmarender("Standard (Osmarender)");
  var layertest1    = new OpenLayers.Layer.OSM("Test (Skunk)", "/skunk/tiles/", {numZoomLevels: 17});
  var layertest2    = new OpenLayers.Layer.OSM("Test (Lesewesen)", "/lesewesen/tiles/", {numZoomLevels: 17});
  vector_layer=new OpenLayers.Layer.Vector("Data", {});

  map.addLayers([ layerpubtran, layerMapnik, layerTah, layertest1, layertest2, vector_layer]);
  map.addControl(new OpenLayers.Control.LayerSwitcher());
  map.addControl(new OpenLayers.Control.Permalink());

  if(start_lon) {
    var lonlat = new OpenLayers.LonLat(start_lon, start_lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    map.setCenter(lonlat, start_zoom);
  }

  style_default=new OpenLayers.Style({"strokeWidth":"3", "strokeColor": "#000000", "strokeOpacity": 0.5, fillOpacity: "0" });
  style_tunnel=new OpenLayers.Style({"strokeWidth":"3", "strokeColor": "#7f7f7f", fillOpacity: "0" });

  redraw_timer=setTimeout("check_redraw()", 300);

  map.events.register("moveend", map, view_changed);
  map.events.register("movestart", map, view_changed_start);
}

window.onload=init;
