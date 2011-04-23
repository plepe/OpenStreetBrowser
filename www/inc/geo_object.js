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
}
