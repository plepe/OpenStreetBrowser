// geo_object - a general class of geographic objects, like osm-elements,
// markers, routes, ...
// every geo_object has to define the following properties before calling
// inherited constructor:
//  .id   - an arbitrary string, identifying this object
//  .type - type of object (e.g. "osm", "marker", "route")
//
// the following properties are optional, but should keep to the standard:
//  .tags - A tags instance
//
// the following functions should be provided:
//  .name() - Returning the name of the object in data_lang
//  .geo()         - a function returning an array of OpenLayers features
//  .geo_center()  - a function returning an array of OpenLayers features

function geo_object() {
  this.type="default";

  // name
  this.name=function() {
    return "default geo object";
  }

  // info
  this.info=function(chapters) {
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


}
