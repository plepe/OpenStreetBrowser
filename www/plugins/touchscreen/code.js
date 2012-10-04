var touchscreen_present=false;
var touchscreen_enabled=false;
var touchscreen_last_touched_link;
var touchscreen_debug_input;
var touchscreen_css;

function touchscreen_debug_init() {
  if(debug_toolbox_register) {
    var dom=document.createElement("div");

    touchscreen_debug_input=dom_create_append(dom, "input");
    var input=touchscreen_debug_input;
    input.type="checkbox";
    input.id="input_debug_toolbox_touchscreen";
    input.onchange=touchscreen_debug_toggle.bind(this, input);

    var label=dom_create_append(dom, "label");
    label.setAttribute("for", "input_debug_toolbox_touchscreen");
    dom_create_append_text(label, lang("touchscreen:debug_name"));

    debug_toolbox_register({
      weight: 0,
      dom: dom
    });
  }
}

function touchscreen_debug_toggle() {
  if(touchscreen_enabled)
    touchscreen_disable();
  else
    touchscreen_enable();
}

function touchscreen_disable() {
  touchscreen_enabled=false;

  unregister_hooks_object("touchscreen");

  // uncheck input in debug
  if(touchscreen_debug_input)
    touchscreen_debug_input.checked=false;
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

  // check input in debug
  if(touchscreen_debug_input)
    touchscreen_debug_input.checked=true;
}

function touchscreen_init() {
  touchscreen_present='ontouchstart' in document.documentElement;

  touchscreen_debug_init();

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

function touchscreen_catch_link(e) {
  var event=e?e:event;

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
      element.set_highlight(event);
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
