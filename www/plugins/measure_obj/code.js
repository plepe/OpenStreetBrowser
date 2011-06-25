var measure_obj_control=null;

function measure_obj_init() {
  if(measure_obj_control)
    return;

  measure_obj_control=new OpenLayers.Control.Measure(OpenLayers.Handler.Path,{
    geodesic: true
  });

  measure_obj_control.events.on({
  });
  map.addControl(measure_obj_control);
}

function measure_obj_info(chapter, ob) {
  var ret="";
  var geo=ob.geo();
  if(!geo)
    return;

  measure_obj_init();

  var length=0;
  var area=0;

  for(var i=0; i<geo.length; i++) {
    var g=geo[i].geometry;

    length+=measure_obj_control.getLength(g, "m");
    area+=measure_obj_control.getArea(g, "m");
  }

  if(length) {
    ret+="Length: "+length.toFixed(3)+" m<br>";
  }
  
  if(area) {
    ret+="Area: "+area.toFixed(3)+" m²<br>";
  }
  
  chapter.push({
    head: lang("measure:head"),
    weight: 1,
    content: ret
  });
}

register_hook("info", measure_obj_info);
