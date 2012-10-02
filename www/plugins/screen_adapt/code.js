var screen_adapt_map_controls={
  'zoom': null
}


function screen_adapt_resize() {
  var map_div=document.getElementById("map");
  if(!map_div)
    return;

  if(map_div.offsetHeight<350) {
    if(screen_adapt_map_controls.zoom.displayClass=="olControlPanZoomBar") {
      map.removeControl(screen_adapt_map_controls.zoom);
      screen_adapt_map_controls.zoom=new OpenLayers.Control.Zoom();
      map.addControl(screen_adapt_map_controls.zoom);
    }
  }
  else {
    if(screen_adapt_map_controls.zoom.displayClass=="olControlZoom") {
      map.removeControl(screen_adapt_map_controls.zoom);
      screen_adapt_map_controls.zoom=new OpenLayers.Control.PanZoomBar();
      map.addControl(screen_adapt_map_controls.zoom);
    }
  }
}

function screen_adapt_init() {
  for(var k in screen_adapt_map_controls) {
    for(var i=0; i<map.controls.length; i++) {
      if(map.controls[i].displayClass=="olControlPanZoomBar") {
	screen_adapt_map_controls.zoom=map.controls[i];
      }
    }

  }
}

register_hook("init", screen_adapt_init);
register_hook("window_resize", screen_adapt_resize);
