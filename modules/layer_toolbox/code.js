var layer_toolbox;

function layer_toolbox_class(options) {
  if(!options)
    options={};
  options.icon=modulekit_file("layer_toolbox", "icon.png");
  options.icon_title=lang("layer_toolbox:name");
  options.weight=5;

  this.inheritFrom=toolbox;
  this.inheritFrom(options);

  register_toolbox(this);

  this.show();

  register_hook("basemap_registered", this.show.bind(this));
  register_hook("overlays_registered", this.show.bind(this));
  register_hook("overlays_visibility_change", this.show.bind(this));
  register_hook("basemap_changebaselayer", this.show.bind(this));
}

layer_toolbox_class.prototype.show=function() {
  dom_clean(this.content);

  var div=dom_create_append(this.content, "div");
  div.className="layer_toolbox";

  var h2=dom_create_append(div, "h2");
  dom_create_append_text(h2, lang("layer_toolbox:head:base_layer"));

  for(var i in basemaps) {
    var li=dom_create_append(div, "div");

    var input=document.createElement("input");
    input.type="radio";
    input.name="layer_toolbox_base_layer";
    input.value=i;
    input.id="layer_toolbox_base_layer_"+i;
    input.onchange=this.select_basemap.bind(this, i, input);
    li.appendChild(input);

    var label=dom_create_append(li, "label");
    label.setAttribute("for", "layer_toolbox_base_layer_"+i);
    dom_create_append_text(label, basemaps[i].name);

    if(map.baseLayer==basemaps[i])
      input.checked=true;
  }

  if(overlays_layers&&(keys(overlays_layers).length)) {
    var h2=dom_create_append(div, "h2");
    dom_create_append_text(h2, lang("layer_toolbox:head:overlays"));

    for(var i in overlays_layers) {
      var li=dom_create_append(div, "div");

      var input=document.createElement("input");
      input.type="checkbox";
      input.name="layer_toolbox_overlays_"+i;
      input.id="layer_toolbox_overlays_"+i;
      input.checked=true;
      input.onchange=this.select_overlay.bind(this, i, input);
      li.appendChild(input);

      var label=dom_create_append(li, "label");
      label.setAttribute("for", "layer_toolbox_overlays_"+i);
      if(overlays_layers[i].options&&overlays_layers[i].options.help)
	label.title=overlays_layers[i].options.help;
      dom_create_append_text(label, overlays_layers[i].name);

      input.checked=overlays_layers[i].getVisibility();
    }
  }
}

layer_toolbox_class.prototype.select_basemap=function(i, input) {
  map.setBaseLayer(basemaps[i]);
}

layer_toolbox_class.prototype.select_overlay=function(i, input) {
  var l=overlays_layers[i];

  l.setVisibility(input.checked);
}

function layer_toolbox_init() {
  layer_toolbox=new layer_toolbox_class();
}

register_hook("init", layer_toolbox_init);
