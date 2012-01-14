function keyshort_map_keydown(done, ev) {
  var action=false;

  // another hook reacted on the keyboard shortcut
  if(done.length)
    return;

  var x=0;
  var y=0;

  // check if we have an action defined for this event
  switch(ev.keyCode) {
    case 33: // PgUp
      map.zoomIn();
      action=true;
      break;
    case 34: // PgDn
      map.zoomOut();
      action=true;
      break;
    case 37: // Left
      x=-1;
      break;   
    case 38: // Up
      y=-1;
      break;
    case 39: // Right
      x=1;
      break;
    case 40: // Down
      y=1;
      break;
  }

  // check special keys
  var multi=10;
  if(ev.ctrlKey)
    multi=25;
  if(ev.shiftKey)
    multi=5;

  // move map
  if((x!=0)||(y!=0)) {
    map.moveByPx(x*multi, y*multi);
    action=true;
  }

  // tell following hooks that an action has already been taken
  if(action)
    done.push("keyshort_map");
}

register_hook("keyshort_keydown", keyshort_map_keydown);
