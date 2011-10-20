var debug_toolbox;
var debug_toolbox_items=[];

function debug_toolbox_register(param) {
  var div=document.createElement("div");
  debug_toolbox.content.appendChild(div);

  debug_toolbox_items.push([ param.weight, div ]);
  div.appendChild(param.dom);
}

function debug_toolbox_init() {
  debug_toolbox=new toolbox({
    icon: "plugins/debug_toolbox/icon.png",
    icon_title: lang("debug_toolbox:name"),
    weight: 10,
  });
  register_toolbox(debug_toolbox);
}

register_hook("init", debug_toolbox_init);
