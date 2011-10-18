function layer_inspect_view_changed() {
  var inspect_div=basemaps.osb.div;
  var div=inspect_div.firstChild;
  var new_tiles=[];

  while(div) {
    if(!div.layer_inspect) {
      div.layer_inspect=dom_create_append(div, "div");
      div.layer_inspect.className="layer_inspect";

      dom_create_append_text(div.layer_inspect, "foo");
    }

    div=div.nextSibling;
  }
}

register_hook("view_changed_delay", layer_inspect_view_changed);
