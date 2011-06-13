var my_maps_toolbox;

function my_maps_init() {
  my_maps_toolbox=new toolbox({
    icon: "plugins/my_maps/icon.png",
    icon_title: "my_maps",
    weight: 5,
  });
  register_toolbox(my_maps_toolbox);

}

register_hook("init", my_maps_init);
