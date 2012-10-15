var debug_toolbox;
var debug_toolbox_items=[];

function debug_toolbox_register(param) {
  var div=document.createElement("div");
  debug_toolbox.content.appendChild(div);

  debug_toolbox_items.push([ param.weight, div ]);
  div.appendChild(param.dom);
}

function debug_toolbox_show() {
  register_toolbox(debug_toolbox);
}

function debug_toolbox_hide() {
  unregister_toolbox(debug_toolbox);
}

function debug_toolbox_init() {
  debug_toolbox=new toolbox({
    icon: modulekit_file("debug_toolbox", "icon.png"),
    icon_title: lang("debug_toolbox:name"),
    weight: 10,
  });
 
  var r=options_get("debug_toolbox");
  if(typeof r=="string")
    r=r.split(/,/);

  if(in_array("show", r))
    debug_toolbox_show();
}

// options integration
function debug_toolbox_options_show(list) {
  var ret=document.createElement("div");
  ret.className="debug_toolbox_options";

  var h4=dom_create_append(ret, "h4");
  dom_create_append_text(h4, lang("debug_toolbox:name"));

  var d=dom_create_append(ret, "div");
  d.className="options_help";

  dom_create_append_text(d, lang("debug_toolbox:help"));

  var d=dom_create_append(ret, "div");
  d.innerHTML=options_checkbox("debug_toolbox", [ "show" ]);

  list.push([ 10, ret ]);
}

function debug_toolbox_options_save() {
  var r=options_checkbox_get("debug_toolbox");
  options_set("debug_toolbox", r);

  if(in_array("show", r))
    debug_toolbox_show();
  else
    debug_toolbox_hide();
}

register_hook("init", debug_toolbox_init);
register_hook("options_show", debug_toolbox_options_show);
register_hook("options_save", debug_toolbox_options_save);
