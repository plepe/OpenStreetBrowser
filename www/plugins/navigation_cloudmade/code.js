// example:
// var nav=new navigation_cloudmade();
// nav.get_route({ start_point: "48.19,16.34", end_point: "48.19,16.37" }, nav_show);
// nav_show in this example is a callback function which gets passed an object
// of type navigation_cloudmade_route (defined below).
//
// At the end of this file you'll find a simple example for calculation a route
//
// IMPORTANT FUNCTIONS
//
// class navigation_cloudmade       - a class to interact with the routing 
//                                    service of cloudmade
//   .get_route(param, callback)    - call to calculate a route
//                                  - param is an assoc. array:
//                                    e.g. { route_type: "foot", lang: "en" }
//                                    start_point, end_point and the list of
//                                    transit_points can be OpenLayers Points
//                                    or { lat: , lon: } in srid 4326
//                                  - callback is function which will be called
//                                    after loading the route. Gets passed the
//                                    route object
//                                  - returns the route object
//  
// class navigation_cloudmade_route - an instance of a calculated route
//   .param                         - holds the parameters when route was
//                                    calculated
//   .vectors                       - Array of OpenLayers Vectors of the route
//   .text                          - Holds the original GPX-File
//   .dom                           - Holds a DOM representation of GPX
//   .show()                        - Show route on drag_layer
//   .hide()                        - Hide route

var navigation_cloudmade_travelwith=[
  { id: "car",
    type: "car"
  },
  { id: "car_shortest",
    type: "car",
    modifier: "shortest"
  },
  { id: "bicycle",
    type: "bicycle"
  },
  { id: "foot",
    type: "foot"
  }
];

function navigation_cloudmade() {
  // get_route
  this.get_route=function(param, _callback) {
    var route=new navigate_cloudmade_route();
    var utm=new OpenLayers.Projection("EPSG:4326");
    callback=_callback;

    if(param.start_point.lat) {
      param.start_point={ lat: param.start_point.lat, lon: param.start_point.lon };
    }
    else if(param.start_point.CLASS_NAME) {
      var p=new OpenLayers.Geometry.Point(param.start_point.x, param.start_point.y);
      var t=p.transform(map.getProjectionObject(), utm);
      param.start_point={ lat: t.y, lon: t.x };
    }

    if(param.end_point.lat) {
      param.end_point={ lat: param.end_point.lat, lon: param.end_point.lon };
    }
    else if(param.end_point.CLASS_NAME) {
      var p=new OpenLayers.Geometry.Point(param.end_point.x, param.end_point.y);
      var t=p.transform(map.getProjectionObject(), utm);
      param.end_point={ lat: t.y, lon: t.x };
    }

    if(param.transit_points)
      for(var i=0; i<param.transit_points.length;i++) {
	var p=new OpenLayers.Geometry.Point(param.transit_points[i].x,
	                                    param.transit_points[i].y);
	var t=p.transform(map.getProjectionObject(), utm);
	param.transit_points[i]={ lat: t.y, lon: t.x };
      }

    if(param.travel_with) {
      var tw;
      for(var i=0; i<navigation_cloudmade_travelwith.length; i++) {
	if(navigation_cloudmade_travelwith[i].id==param.travel_with)
	  tw=navigation_cloudmade_travelwith[i];
      }

      if(!tw) {
	alert("navigation_cloudmade :: travel with wrong");
	return;
      }

      param.route_type=tw.type;
      if(tw.modifier)
	param.route_type_modifier=tw.modifier;
      delete(param.travel_with);
    }

    route.param=param;
    ajax_direct("plugins/navigation_cloudmade/call.php", param, this.recv.bind(this, route));
  }

  // recv
  this.recv=function(route, response) {
    route.recv(response);

    if(callback)
      callback(route);
  }

  // constructor
  var callback;
}

function navigate_cloudmade_route() {
  // recv
  this.recv=function(response) {
    this.dom=response.responseXML;
    this.text=response.responseText;

    if(!this.dom) {
      alert("Failed to request route:\n"+this.text);
      return;
    }

    var utm=new OpenLayers.Projection("EPSG:4326");

    var wpts=this.dom.getElementsByTagName("wpt");
    var points=[];
    for(var i=0; i<wpts.length; i++) {
      var wpt=wpts[i];
      points.push(new OpenLayers.Geometry.Point(wpt.getAttribute("lon"), wpt.getAttribute("lat")).transform(utm, map.getProjectionObject()));
    }

    this.vectors=[new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(points), null, { strokeWidth: 3, strokeColor: '#0000ff' })];
  }

  // show
  this.show=function() {
    drag_layer.addFeatures(this.vectors);
  }

  // hide
  this.hide=function() {
    drag_layer.removeFeatures(this.vectors);
  }

  // remove 
  this.remove=function() {
    this.hide();
  }

  // print_instructions
  this.print_instructions=function(div) {
    var rtes=this.dom.getElementsByTagName("rte");
    if(!rtes.length) {
      alert("No route instructions found");
      return;
    }

    var ul=dom_create_append(div, "ul");

    var pts=rtes[0].getElementsByTagName("rtept");
    for(var i=0; i<pts.length; i++) {
      var pt=pts[i];

      var desc=pt.getElementsByTagName("desc");
      if(desc.length) {
	var li=dom_create_append(ul, "li");

	dom_create_append_text(li, desc[0].firstChild.nodeValue);
      }
    }
  }

  // constructor
}

//// Example ////
// function nav_show(route) {
//   route.show();
// }
// 
// var nav_ml=[];
// var nav=new navigation_cloudmade();
// function nav_init(marker) {
//   nav_ml.push(marker.feature.geometry);
//   if(nav_ml.length==4) {
//     nav.get_route({ start_point: nav_ml[0], end_point: nav_ml[3], transit_points: [ nav_ml[1], nav_ml[2] ] }, nav_show);
//   }
// }
//
// register_hook("marker_created", nav_init);
