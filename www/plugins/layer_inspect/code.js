var layer_inspect_active=false;
var layer_inspect_request;

function layer_inspect_callback(ret) {
  ret=ret.return_value;

  layer_inspect_request=false;

  var inspect_div=map.baseLayer.div;
  var div=inspect_div.firstChild;

  while(div) {
    var imgs=div.getElementsByTagName("img");
    if(imgs.length&&div.layer_inspect) {
      var stat;
      if(stat=ret[imgs[0].src]) {
	dom_clean(div.layer_inspect);
	dom_create_append_text(div.layer_inspect, stat);
      }
    }

    div=div.nextSibling;
  }
}

function layer_inspect_view_changed() {
  if(!layer_inspect_active)
    return;
  if(layer_inspect_request)
    return;

  var inspect_div=map.baseLayer.div;
  var div=inspect_div.firstChild;
  var new_tiles=[];

  while(div) {
    var src=null;
    var imgs=div.getElementsByTagName("img");
    if(imgs.length&&imgs[0].src) {
      src=imgs[0].src;

    if(!div.layer_inspect) {
      div.layer_inspect=dom_create_append(div, "div");
      div.layer_inspect.className="layer_inspect";
    }

    if(div.layer_inspect.src!=src) {
	div.layer_inspect.src=src;
	new_tiles.push(src);

	dom_clean(div.layer_inspect);
      }
    }

    div=div.nextSibling;
  }

  if(new_tiles.length) {
    ajax("layer_inspect", {}, json_encode(new_tiles), layer_inspect_callback);
    layer_inspect_request=true;
  }
}

function layer_inspect_activate() {
  layer_inspect_view_changed();
}

function layer_inspect_deactivate() {
  var inspect_div=map.baseLayer.div;
  var div=inspect_div.firstChild;

  while(div) {
    if(div.layer_inspect) {
      div.removeChild(div.layer_inspect);
      delete(div.layer_inspect);
    }

    div=div.nextSibling;
  }
}

function layer_inspect_toggle(input) {
  layer_inspect_active=input.checked;

  if(layer_inspect_active)
    layer_inspect_activate();
  else
    layer_inspect_deactivate();
}

function layer_inspect_init() {
  var dom=document.createElement("div");
  dom.className="layer_inspect_toolbox";

  var input=dom_create_append(dom, "input");
  input.type="checkbox";
  input.onchange=layer_inspect_toggle.bind(this, input);
  
  dom_create_append_text(dom, lang("layer_inspect:name"));

  if(debug_toolbox_register) {
    debug_toolbox_register({
      weight: 0,
      dom: dom
    });
  }
}

register_hook("init", layer_inspect_init);
register_hook("view_changed_delay", layer_inspect_view_changed);
