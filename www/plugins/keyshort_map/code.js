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
    case "keyshort_map:scroll_north":
      y=-1;
      break;
    case "keyshort_map:scroll_east":
      x=+1;
      break;
    case "keyshort_map:scroll_south":
      y=+1;
      break;
    case "keyshort_map:scroll_west":
      x=-1;
      break;
  }

  // move map
  if((x!=0)||(y!=0)) {
    map.moveByPx(x*multi, y*multi);
  }

  return false;
}

register_keyshort("keyshort_map:zoom_in", keyshort_map, "pageup");
register_keyshort("keyshort_map:zoom_out", keyshort_map, "pagedown");
register_keyshort("keyshort_map:scroll_north", keyshort_map, "up");
register_keyshort("keyshort_map:scroll_east", keyshort_map, "right");
register_keyshort("keyshort_map:scroll_south", keyshort_map, "down");
register_keyshort("keyshort_map:scroll_west", keyshort_map, "left");
register_keyshort("keyshort_map:scroll_north:fast", keyshort_map, "ctrl_up");
register_keyshort("keyshort_map:scroll_east:fast", keyshort_map, "ctrl_right");
register_keyshort("keyshort_map:scroll_south:fast", keyshort_map, "ctrl_down");
register_keyshort("keyshort_map:scroll_west:fast", keyshort_map, "ctrl_left");
register_keyshort("keyshort_map:scroll_north:slow", keyshort_map, "shift_up");
register_keyshort("keyshort_map:scroll_east:slow", keyshort_map, "shift_right");
register_keyshort("keyshort_map:scroll_south:slow", keyshort_map, "shift_down");
register_keyshort("keyshort_map:scroll_west:slow", keyshort_map, "shift_left");
