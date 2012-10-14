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

    try {
      length+=measure_obj_control.getLength(g, "m");
    } catch (e) {
      // ignore ...
    }
    try {
      area+=measure_obj_control.getArea(g, "m");
    } catch(e) {
      // ignore ...
    }
  }

  var found=false;

  ret+="<ul>\n";
  if(length) {
    found=true;
    var x=units_format_length(length)
    ret+="<li>"+lang("measure_obj:length", 0, x)+"</li>\n";
  }
  
  if(area) {
    found=true;
    var x=units_format_area(area)
    ret+="<li>"+lang("measure_obj:area", 0, x)+"</li>\n";
  }
  ret+="</ul>\n";
  
  if(found) {
    chapter.push({
      head: lang("measure_obj:head"),
      weight: 1,
      content: ret
    });
  }
}

register_hook("info", measure_obj_info);
