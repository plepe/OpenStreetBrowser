var layer_toolbox;

function layer_toolbox_class(options) {
  if(!options)
    options={};
  options.icon="plugins/layer_toolbox/icon.png";
  options.icon_title=lang("layer_toolbox:name");
  options.weight=5;

  this.inheritFrom=toolbox;
  this.inheritFrom(options);

  register_toolbox(this);

  this.show();
}

layer_toolbox_class.prototype.show=function() {
  dom_clean(this.content);

  var div=dom_create_append(this.content, "div");
  div.className="layer_toolbox";

  var h2=dom_create_append(div, "h2");
  dom_create_append_text(h2, lang("layer_toolbox:head:base_layer"));

  var ul=dom_create_append(div, "ul");
  for(var i in basemaps) {
    var li=dom_create_append(ul, "li");

    var input=document.createElement("input");
    input.type="radio";
    input.name="layer_toolbox_base_layer";
    input.value=i;
    li.appendChild(input);

    dom_create_append_text(li, basemaps[i].name);
  }

  if(overlays_layers&&(keys(overlays_layers).length)) {
    var h2=dom_create_append(div, "h2");
    dom_create_append_text(h2, lang("layer_toolbox:head:overlays"));

    var ul=dom_create_append(div, "ul");
    for(var i in overlays_layers) {
      var li=dom_create_append(ul, "li");

      var input=document.createElement("input");
      input.type="checkbox";
      input.name="layer_toolbox_overlays_"+i;
      input.checked=true;
      li.appendChild(input);

      dom_create_append_text(li, overlays_layers[i].name);
    }
  }
}

function layer_toolbox_init() {
  layer_toolbox=new layer_toolbox_class();
}

register_hook("init", layer_toolbox_init);
