var measure_toolbox;

function measure_init() {
  measure_toolbox=new toolbox({
    icon: "img/toolbox_measure.png",
    icon_title: "measurements",
    weight: 5,
  });
  register_toolbox(measure_toolbox);

  var text = "<i>Measurements</i><br/><br/>At first set measure points on the map.<br/><br/>distance: 0m<br/>area: 0m²<br/><br/>";
  measure_toolbox.content.innerHTML=text;
}

register_hook("init", measure_init);
