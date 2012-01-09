var debug_toolbox_layer_switcher;

function debug_toolbox_layer_switcher_toggle() {
  if(!debug_toolbox_layer_switcher) {
    debug_toolbox_layer_switcher=new OpenLayers.Control.LayerSwitcher();
    map.addControl(debug_toolbox_layer_switcher);
  }
  else {
    map.removeControl(debug_toolbox_layer_switcher);
    debug_toolbox_layer_switcher=null;
  }
}

function debug_toolbox_layer_switcher_init() {
  var dom=document.createElement("div");
  dom.className="debug_toolbox_layer_switcher";

  var input=dom_create_append(dom, "input");
  input.type="checkbox";
  input.id="input_debug_toolbox_layer_switcher";
  input.onchange=debug_toolbox_layer_switcher_toggle.bind(this, input);
  
  var label=dom_create_append(dom, "label");
  label.setAttribute("for", "input_debug_toolbox_layer_switcher");
  dom_create_append_text(label, lang("debug_toolbox_layer_switcher:name"));

  if(debug_toolbox_register) {
    debug_toolbox_register({
      weight: 0,
      dom: dom
    });
  }
}

register_hook("init", debug_toolbox_layer_switcher_init);
