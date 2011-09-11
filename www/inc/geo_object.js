// geo_object - a general class of geographic objects, like osm-elements,
// markers, routes, ...
// every geo_object has to define the following properties before calling
// inherited constructor:
//  .id   - an arbitrary string, identifying this object
//  .type - type of object (e.g. "osm", "marker", "route")
//
//  .show() - Show the object(s) on the map
//  .hide() - Hide the object(s) from the map
//
// the following properties are optional, but should keep to the standard:
//  .tags - A tags instance
//
// the following functions should be provided:
//  .name() - Returning the name of the object in data_lang
//  .geo()         - a function returning an array of OpenLayers features
//  .geo_center()  - a function returning an array of OpenLayers features
//
// if the object is a kind of relations it can have members:
//  .members - Array of all members (which are geo_objects themselves)
//  .member_roles - Array of roles of members (same index)
//
// Hooks geo_objects can call:
//  'geo_object_change' - If something (geometry, id) has changed

function geo_object() {
  this.type="default";

  // name
  this.name=function() {
    return "default geo object";
  }

  // info
  this.info=function(chapters) {
  }

  // show
  this.show=function() {
  }

  // hide
  this.hide=function() {
  }

  // geo
  this.geo=function() {
  }

  // geo
  this.geo_center=function() {
  }

  // geo_get_extent
  this.get_extent=function() {
    var extent=new OpenLayers.Bounds();
    var geos=this.geo();

    for(var i=0; i<geos.length; i++) {
      extent.extend(geos[i].geometry.getBounds());
    }

    return extent;
  }

  // geo_zoom_to
  this.geo_zoom_to=function() {
    var extent=this.get_extent();

    var zoom=map.getZoomForExtent(extent);
    if(zoom>15)
      zoom=15;

    var center=this.geo_center();
    if(center&&center.length)
      center=center[0];
    if(center&&center.geometry)
      center=center.geometry;
    else
      center=extent.getCenterPixel();

    pan_to_highlight(center.x, center.y, zoom);
  }

  // lonlat
  this.lonlat=function() {
    var geo;

    geo=this.geo_center();
    if(!geo)
      geo=this.geo();

    if(!geo)
      alert("Could not get geometry of empty object");

    // check validity
    if((geo.length<1)||(!geo[0].geometry)) {
      alert("Could not get geometry of object");
      return;
    }

    // calculate lat/lon of object
    var poi=geo[0].geometry.getCentroid()
	      .transform(map.getProjectionObject(),
			 new OpenLayers.Projection("EPSG:4326"));

    // return lon, lat
    return { lon: poi.x, lat: poi.y };
  }
}
