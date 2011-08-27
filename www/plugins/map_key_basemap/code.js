function map_key_basemap() {
  this.inheritFrom=map_key_entry;
  this.inheritFrom("basemap");
  this.type="map_key_basemap";

  this.visibility=function() {
    return true;
  }

  // constructor
}

function map_key_basemap_init() {
  new map_key_basemap();
}

register_hook("init", map_key_basemap_init);
