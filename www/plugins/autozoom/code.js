function autozoom_options_show(list) {
  var ret1;
  ret1 ="<h4>"+t("options:autozoom")+"</h4>\n";
  ret1+="<div class='options_help'>"+t("help:autozoom")+"</div>\n";
  ret1+=options_radio("autozoom", [ "pan", "move", "stay" ]);
  var d=document.createElement("div");
  d.innerHTML=ret1;

  list.push([ 0, d ]);
}

function autozoom_options_save() {
  options_set("autozoom", options_radio_get("autozoom"));
}

function autozoom_info_show(info, ob) {
  if(!info.buttons)
    return;

  if(ob.geo_center()) {
    var a=button_dom(lang_dom("info_zoom",
                     ob.geo_zoom_to.bind(ob)));
    info.buttons.add([0, a]);
  }
}

register_hook("options_show", autozoom_options_show);
register_hook("options_save", autozoom_options_save);
register_hook("info_show", autozoom_info_show);
