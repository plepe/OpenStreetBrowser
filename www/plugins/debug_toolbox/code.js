var debug_toolbox;

function debug_toolbox_init() {
  debug_toolbox=new toolbox({
    icon: "plugins/debug_toolbox/icon.png",
    icon_title: lang("debug_toolbox:name"),
    weight: 10,
  });
  register_toolbox(debug_toolbox);
}

register_hook("init", debug_toolbox_init);
