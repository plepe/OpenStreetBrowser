//Karte, Haus, Zielflagge, Marker, Lineal, (Hilfe)

var timer, i=0, details_style, search_style, toolbox_style, details_top, oldtop=180, lastview, toolbox_active, toolbox_locked=0;

function toolbox_slide_fillwithtext(text) {
  return;
  if(toolbox_locked==0){
    toolbox_locked=1;
    document.getElementById("toolbox").innerHTML=text;
    details_style=document.getElementById("details").style;
    document.getElementById("toolbox").style.display="block"
    details_top=document.getElementById("toolbox").offsetHeight + 180;
    details_style.top=details_top+"px";

    toolbox_style=document.getElementById("toolbox").style;
    search_style=document.getElementById("search").style;
    if(oldtop<details_top) {
      timer = window.setInterval("toolbox_slide(oldtop,details_top,1)",10);
    } else {
      timer = window.setInterval("toolbox_slide(oldtop,details_top,-1)",10);
    }
  }
}

function toolbox_slide_slide(from, to, direction) {
  var top = from+(direction*10*i);
  details_style.top=top+"px";
  search_style.top=top-37+"px";

  if(direction==1) {
    toolbox_style.clip="rect(0px, 250px, "+(top-from+oldtop-180)+"px, 0px)";
    if(top>=to) {
      window.clearInterval(timer);
      i=0;
      details_style.top=to+"px";
      search_style.top=to-37+"px";
      toolbox_style.clip="rect(0px, 250px, "+(to-from+oldtop-180)+"px, 0px)";
      oldtop=to;
      toolbox_locked=0;
    }
  } else if(direction==-1) {
    toolbox_style.clip="rect(0px, 250px, "+(top-180)+"px, 0px)";
    if(top<=to) {
      window.clearInterval(timer);
      i=0;
      details_style.top=to+"px";
      search_style.top=to-37+"px";
      toolbox_style.clip="rect(0px, 250px, "+(to-180)+"px, 0px)";
      oldtop=to;
      toolbox_locked=0;
    }
  } else {
    window.clearInterval(timer); //just to prevent running timers on wrong direction-input
    i=0;
  }
  i++;
}
