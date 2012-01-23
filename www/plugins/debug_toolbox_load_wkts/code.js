function debug_toolbox_load_wkts_show() {
}

function debug_toolbox_load_wkts_init() {
  var div=document.createElement("div");

  var a=dom_create_append(div, "a");
  a.href="javascript:debug_toolbox_load_wkts_show()";
  dom_create_append_text(a, lang("debug_toolbox_load_wkts:load"));

  debug_toolbox_register({
    weight: 5,
    dom: div
  });
}

register_hook("init", debug_toolbox_load_wkts_init);
