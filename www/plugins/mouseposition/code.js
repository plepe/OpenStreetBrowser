var mouseposition_control;

function mouseposition_init() {
  mouseposition_control=new OpenLayers.Control.MousePosition();
  map.addControl(mouseposition_control);

  mouseposition_control.formatOutput=function(lonlat) {
    ret="";

    ret+="z"+map.zoom+", ";
    ret+=lonlat.lon.toFixed(5);
    ret+=", ";
    ret+=lonlat.lat.toFixed(5);

    return ret;
  }
}

register_hook("init", mouseposition_init);
