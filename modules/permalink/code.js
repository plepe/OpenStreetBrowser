var permalink_current;
var permalink;

// get_permalink ... returns current view as hash array
// PARAMETERS:
//   none
// RETURNS:
//   a hash array describing the current view
function get_permalink() {
  var center = ol.proj.transform(map.getView().getCenter(), "EPSG:3857", "EPSG:4326");

  var permalink = {
    zoom: map.getView().getZoom(),
    lon: center[0].toFixed(5),
    lat: center[1].toFixed(5)
  };

  if(location_params.obj)
    permalink.obj=location_params.obj;

  call_hooks("get_permalink", permalink);

  permalink_current=permalink;

  return permalink;
}

// update_permalink ... forces an update of the permalink
function update_permalink() {
  get_permalink();
  call_hooks("permalink_update", permalink_current);

  if(!permalink)
    permalink = document.getElementById("permalink");

  permalink.href = url(permalink_current, true);
}

register_hook("view_changed", update_permalink);
