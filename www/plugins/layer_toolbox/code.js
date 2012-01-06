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
}
function layer_toolbox_init() {
  layer_toolbox=new layer_toolbox_class();
}

register_hook("init", layer_toolbox_init);
