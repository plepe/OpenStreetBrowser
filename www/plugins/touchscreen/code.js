var touchscreen_enabled;
var touchscreen_last_touched_link;

function touchscreen_init() {
  touchscreen_enabled='ontouchstart' in document.documentElement;
}

function touchscreen_uncatch_link() {
  if(!touchscreen_last_touched_link)
    return;

  var ob=touchscreen_last_touched_link;
  touchscreen_last_touched_link=null;

  // remove 'selected' class
  del_css_class(ob, "selected");

  // set onclick to touchscreen_catch_link
  if(ob.onclick)
    ob.onclick_touchscreen=ob.onclick;

  ob.onclick=touchscreen_catch_link;

  // unset highlight
  var element;
  if(element=ob.parentNode.element) {
    if(element.unset_highlight)
      element.unset_highlight({target:ob});
  }
}

function touchscreen_catch_link() {
  // first 'un'-catch old link
  touchscreen_uncatch_link();

  // store currently clicked link
  var ob=event.target;
  touchscreen_last_touched_link=ob;

  // reset onclick to original onclick-event
  if(ob.onclick_touchscreen) {
    ob.onclick=ob.onclick_touchscreen;
    ob.onclick_touchscreen=null;
  }
  else
    ob.onclick=null;

  // add css class 'selected'
  add_css_class(ob, "selected");

  // show highlight (if available)
  var element;
  if(element=ob.parentNode.element) {
    if(element.set_highlight)
      element.set_highlight({target:ob});
  }

  return false;
}

function touchscreen_mangle_list_links(list, node) {
  var links=node.getElementsByTagName("a");

  // modify all links in list
  for(var i=0; i<links.length; i++) {
    var ob=links[i];
    if(ob.onclick)
      ob.onclick_touchscreen=ob.onclick;

    // set onclick event
    ob.onclick=touchscreen_catch_link;

    // remove onmouse-events
    ob.onmouseover=null;
    ob.onmouseout=null;
  }
}

register_hook("init", touchscreen_init);
register_hook("list_shown", touchscreen_mangle_list_links);
