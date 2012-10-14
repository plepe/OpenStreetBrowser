function keyshort_map(id) {
  var x=0, y=0;
  var multi=10;
  var m;

  if(m=id.match(/^(keyshort_map:[^:]*):fast/)) {
    id=m[1];
    multi=25;
  }
  else if(m=id.match(/^(keyshort_map:[^:]*):slow/)) {
    id=m[1];
    multi=5;
  }

  switch(id) {
    case "keyshort_map:zoom_in":
      map.zoomIn();
      break;
    case "keyshort_map:zoom_out":
      map.zoomOut();
      break;
    case "keyshort_map:pan_north":
      y=-1;
      break;
    case "keyshort_map:pan_east":
      x=+1;
      break;
    case "keyshort_map:pan_south":
      y=+1;
      break;
    case "keyshort_map:pan_west":
      x=-1;
      break;
  }

  // move map
  if((x!=0)||(y!=0)) {
    map.moveByPx(x*multi, y*multi);
  }

  return false;
}

function keyshort_map_init() {
  register_keyshort("keyshort_map:zoom_in", keyshort_map, "pageup", lang("zoom_in"));
  register_keyshort("keyshort_map:zoom_out", keyshort_map, "pagedown", lang("zoom_out"));
  register_keyshort("keyshort_map:pan_north", keyshort_map, "up", lang("pan_north"));
  register_keyshort("keyshort_map:pan_east", keyshort_map, "right", lang("pan_east"));
  register_keyshort("keyshort_map:pan_south", keyshort_map, "down", lang("pan_south"));
  register_keyshort("keyshort_map:pan_west", keyshort_map, "left", lang("pan_west"));
  register_keyshort("keyshort_map:pan_north:fast", keyshort_map, "ctrl_up", lang("pan_north")+" "+lang("keyshort_map:fast"));
  register_keyshort("keyshort_map:pan_east:fast", keyshort_map, "ctrl_right", lang("pan_east")+" "+lang("keyshort_map:fast"));
  register_keyshort("keyshort_map:pan_south:fast", keyshort_map, "ctrl_down", lang("pan_south")+" "+lang("keyshort_map:fast"));
  register_keyshort("keyshort_map:pan_west:fast", keyshort_map, "ctrl_left", lang("pan_west")+" "+lang("keyshort_map:fast"));
  register_keyshort("keyshort_map:pan_north:slow", keyshort_map, "shift_up", lang("pan_north")+" "+lang("keyshort_map:slow"));
  register_keyshort("keyshort_map:pan_east:slow", keyshort_map, "shift_right", lang("pan_east")+" "+lang("keyshort_map:slow"));
  register_keyshort("keyshort_map:pan_south:slow", keyshort_map, "shift_down", lang("pan_south")+" "+lang("keyshort_map:slow"));
  register_keyshort("keyshort_map:pan_west:slow", keyshort_map, "shift_left", lang("pan_west")+" "+lang("keyshort_map:slow"));
}

register_hook("init", keyshort_map_init);
