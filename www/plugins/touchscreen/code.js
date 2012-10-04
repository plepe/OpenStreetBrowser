var touchscreen_present=false;
var touchscreen_enabled=false;
var touchscreen_last_touched_link;
var touchscreen_css;

function touchscreen_disable() {
  touchscreen_enabled=false;

  unregister_hooks_object("touchscreen");

  touchscreen_css.parentNode.removeChild(touchscreen_css);
}

function touchscreen_enable() {
  touchscreen_enabled=true;

  // activate mangling links of lists
  register_hook("list_shown", touchscreen_mangle_list_links, "touchscreen");

  // load additional css file
  touchscreen_css=document.createElement("link");
  touchscreen_css.rel="stylesheet";
  touchscreen_css.type="text/css";
  touchscreen_css.href="plugins/touchscreen/touchscreen.css";
  document.head.appendChild(touchscreen_css);
}

function touchscreen_init() {
  touchscreen_present='ontouchstart' in document.documentElement;

  if(touchscreen_present)
    touchscreen_enable();
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
