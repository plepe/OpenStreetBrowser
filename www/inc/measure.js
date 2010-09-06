var measure_toolbox;

function measure_activate() {
  polygon_control.activate();
  map.div.style.cursor="crosshair";
}

function measure_deactivate() {
  polygon_control.deactivate();
  map.div.style.cursor="";
}

function measure_click(pos) {
  measure_toolbox.activate();
}

function measure_init() {
  measure_toolbox=new toolbox({
    icon: "img/toolbox_measure.png",
    icon_title: "measurements",
    callback_activate: measure_activate,
    callback_deactivate: measure_deactivate,
    weight: 5,
  });
  register_toolbox(measure_toolbox);
  contextmenu_add("img/toolbox_measure.png", "measurement tool", measure_click);

  var text = "<i>Measurements</i><br/><br/>At first set measure points on the map.<br/><br/>distance: 0m<br/>area: 0m²<br/><br/>";
  measure_toolbox.content.innerHTML=text;
}

register_hook("init", measure_init);
