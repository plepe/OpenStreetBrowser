//Karte, Haus, Zielflagge, Marker, Lineal, (Hilfe)

var timer, i=0, details_style, search_style, toolbox_style, details_top, oldtop=180, lastview, toolbox_active, toolbox_locked=0;

function toolbox_init() {
  if(((location.hash=="") || (location.hash=="#")) && (cookie_read("start_value")==null)) {
    toolbox_map();
  } else {
    document.getElementById("toolbox").style.display = "none";
    lastview=cookie_read("_osb_location");
    toolbox_start(cookie_read("start_value"));
  }
}

function toolbox_map() {
  if((toolbox_active=="map")||(toolbox_locked==1)) {
    toolbox_hide();
    return;
  }
  toolbox_active="map";
  document.getElementById("toolbox1").className="toolboxbutton_active";
  document.getElementById("toolbox2").className="toolboxbutton";
  document.getElementById("toolbox3").className="toolboxbutton";
  document.getElementById("toolbox4").className="toolboxbutton";

  var text = "<i>"+t("start:choose")+":</i><br><form id=\"startform\" style=\"margin-bottom:3px;\">";
  if (navigator.geolocation) {
    text += "<input type=\"radio\" name=\"start_value\" id=\"geolocation\" value=\"geolocation\"><label for=\"geolocation\">"+t("start:geolocation")+"</label></br>";
  }
  if(lastview=cookie_read("_osb_location")) {
    text += "<input type=\"radio\" name=\"start_value\" id=\"lastview\" value=\"lastview\"><label for=\"lastview\">"+t("start:lastview")+"</label></br>";
  }
  if(cookie_read("_osb_permalink")) {
    text += "<input type=\"radio\" name=\"start_value\" id=\"savedview\" value=\"savedview\"><label for=\"savedview\">"+t("start:savedview")+"</label></br>";
  }
  text += "<input type=\"radio\" name=\"start_value\" id=\"startnormal\" value=\"startnormal\" checked><label for=\"startnormal\">"+t("start:startnormal")+"</label></br>";
  text += "</br><input type=\"button\" name=\"start\" value=\"ok\" onclick=\"toolbox_options()\"><input type=\"checkbox\" name=\"start_save\" id=\"save\" value=\"save\"><label for=\"save\">"+t("start:remember")+"</label></br></form>";

  toolbox_fillwithtext(text);
}

function toolbox_home() {
  if((toolbox_active=="home")||(toolbox_locked==1)){
    toolbox_hide();
    return;
  }
  toolbox_active="home";
  document.getElementById("toolbox1").className="toolboxbutton";
  document.getElementById("toolbox2").className="toolboxbutton_active";
  document.getElementById("toolbox3").className="toolboxbutton";
  document.getElementById("toolbox4").className="toolboxbutton";

  var text = "<i>Home</i><br/><br/>select your home<br/>blablabla<br/><br/>";
  toolbox_fillwithtext(text);
}

function toolbox_favorites() {
  if((toolbox_active=="favorites")||(toolbox_locked==1)){
    toolbox_hide();
    return;
  }
  toolbox_active="favorites";
  document.getElementById("toolbox1").className="toolboxbutton";
  document.getElementById("toolbox2").className="toolboxbutton";
  document.getElementById("toolbox3").className="toolboxbutton_active";
  document.getElementById("toolbox4").className="toolboxbutton";

  var text = "Favorites";
  toolbox_fillwithtext(text);
}

function toolbox_measure() {
  if((toolbox_active=="measure")||(toolbox_locked==1)){
    toolbox_hide();
    return;
  }
  toolbox_active="measure";
  document.getElementById("toolbox1").className="toolboxbutton";
  document.getElementById("toolbox2").className="toolboxbutton";
  document.getElementById("toolbox3").className="toolboxbutton";
  document.getElementById("toolbox4").className="toolboxbutton_active";

  var text = "Measure";
  toolbox_fillwithtext(text);
}

function toolbox_fillwithtext(text) {
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

function toolbox_hide() {
  if(toolbox_locked==0){
    toolbox_locked=1;
    details_style=document.getElementById("details").style;
    search_style=document.getElementById("search").style;
    //document.getElementById("toolbox").style.display = "none";
    timer = window.setInterval("toolbox_slide(details_top,180,-1)",10);
    document.getElementById("toolbox1").className="toolboxbutton";
    document.getElementById("toolbox2").className="toolboxbutton";
    document.getElementById("toolbox3").className="toolboxbutton";
    document.getElementById("toolbox4").className="toolboxbutton";
    toolbox_active="";
  }
}

function toolbox_slide(from, to, direction) {
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

function toolbox_options() {
  var form=document.getElementById("startform");
  var start_value=null;
  for(var i=0; i<form.elements["start_value"].length; i++) {
    if(form.elements["start_value"][i].checked) {
      start_value=form.elements["start_value"][i].value;
    }
  }
  if((form.elements["start_save"].checked)&&(start_value!=null)) {
    cookie_write("start_value",start_value);
  } else {
    cookie_delete("start_value");
  }
  toolbox_start(start_value);
  toolbox_hide();
}

function toolbox_start(start_value) {
  switch (start_value) {
    case "geolocation":
      geo_init();
      break;
    case "lastview":
      var lonlat=lastview.split("|");
      if(map) {
        var coords = new OpenLayers.LonLat(lonlat[0], lonlat[1]).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
        map.setCenter(coords,lonlat[2]);
      } else { 
        start_lon=lonlat[0];
        start_lat=lonlat[1];
        start_zoom=lonlat[2];
      }
      break;
    case "savedview":
      var lonlat=cookie_read("_osb_permalink").split("|");
      if(map) {
        var coords = new OpenLayers.LonLat(lonlat[0], lonlat[1]).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
        map.setCenter(coords,lonlat[2]);
      } else {
        start_lon=lonlat[0];
        start_lat=lonlat[1];
        start_zoom=lonlat[2];
      }

      break;
    case "startnormal":
      break;
  }
}
