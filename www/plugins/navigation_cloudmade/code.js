// example:
// var nav=new navigation_cloudmade();
// nav.get_route({ start_point: "48.19,16.34", end_point: "48.19,16.37" }, nav_show);
// nav_show in this example is a callback function which gets passed an object
// of type navigation_cloudmade_route (defined below).
//
// IMPORTANT FUNCTIONS
//
// class navigation_cloudmade       - a class to interact with the routing 
//                                    service of cloudmade
//   .get_route(param, callback)    - call to calculate a route
//                                  - param is an assoc. array:
//                                    e.g. { route_type: "foot", lang: "en" }
//                                    start_point, end_point and the list
//                                    transit_points can be OpenLayers objects
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

function navigation_cloudmade() {
  // get_route
  this.get_route=function(param, _callback) {
    var route=new navigate_cloudmade_route();
    route.param=param;
    callback=_callback;
 
    ajax_direct("plugins/navigation_cloudmade/call.php", param, this.recv.bind(this, route));
  }

  // recv
  this.recv=function(route, response) {
    route.recv(response);

    callback(route);
    return;
  }

  // constructor
  var callback;
}

function navigate_cloudmade_route() {
  // recv
  this.recv=function(response) {
    this.dom=response.responseXML;
    this.text=response.responseText;

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

  // constructor
}
