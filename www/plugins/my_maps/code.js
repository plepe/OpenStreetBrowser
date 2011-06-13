var my_maps_toolbox;
var my_maps_control;

function my_maps_activate() {
  my_maps_control.activate();
}

function my_maps_deactivate() {
  my_maps_control.deactivate();
}

function my_maps_init() {
  // create toolbox
  my_maps_toolbox=new toolbox({
    icon: "plugins/my_maps/icon.png",
    icon_title: "my_maps",
    weight: 5,
    callback_activate: my_maps_activate,
    callback_deactivate: my_maps_deactivate,
  });
  register_toolbox(my_maps_toolbox);

  // add a control to the map - to be (de)activated when toolbox is
  // (de)activated
  my_maps_control=new OpenLayers.Control.DrawFeature(vector_layer,
    OpenLayers.Handler.Path);
  map.addControl(my_maps_control);
}

register_hook("init", my_maps_init);
