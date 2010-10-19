var measure_toolbox;
var measure_control;

function measure_handle(event) {
  var geometry = event.geometry;
  var units = event.units;
  var order = event.order;
  var measure = event.measure;
  var measure_text = document.getElementById("measure_text");
  var out = "";
  if(order == 1) {
    out += "distance: " + measure.toFixed(3) + " " + units;
  } else {
    out += "area: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
  }
  measure_text.innerHTML = out;
}


function measure_activate() {
  measure_control.activate();
  map.div.style.cursor="crosshair";
}

function measure_deactivate() {
  measure_control.deactivate();
  map.div.style.cursor="";
}

function measure_click(pos) {
  measure_toolbox.activate(1);
  measure_activate();
}

function measure_init() {
  measure_control=new OpenLayers.Control.Measure(OpenLayers.Handler.Path,{});
  measure_control.events.on({
    "measure": measure_deactivate,
    "measurepartial": measure_handle
  });
  map.addControl(measure_control);

  measure_toolbox=new toolbox({
    icon: "plugins/measure/icon.png",
    icon_title: "measurements",
    callback_activate: measure_activate,
    callback_deactivate: measure_deactivate,
    weight: -2,
  });
  register_toolbox(measure_toolbox);
  contextmenu_add("plugins/measure/icon.png", "measurement tool", measure_click);

  var text = "<i>Measurements</i><br/><br/><div id='measure_text'>Start your measurements by selecting your way on the map...</div><br/><br/>";
  measure_toolbox.content.innerHTML=text;
}

register_hook("init", measure_init);
