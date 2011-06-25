function measure_obj_info(chapter, ob) {
  var ret="";
  var geo=ob.geo();
  if(!geo)
    return;

  var length=0;
  var area=0;

  for(var i=0; i<geo.length; i++) {
    var g=geo[i].geometry;

    length+=measure_control.getLength(g, "m");
    area+=measure_control.getArea(g, "m");
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
