//Karte, Haus, Zielflagge, Marker, Lineal, (Hilfe)

var timer, i=0, details_style, details_top, lastview;

function start_show() {
  if(!(location.hash) && (cookie_read("start_value")==null)) {
    var n=0;
    var ret = "<img src=\"close.png\" onClick=\"start_hide()\" style=\"border:0px none ; margin:0px; padding:0px; position:absolute; right:4px; top:3px; width:12px; height:12px; -moz-user-select:none; cursor:pointer; z-index:10;\"/>"+t("start:choose")+":<br><form id=\"startform\" style=\"margin-bottom:3px;\">";
    
    if (navigator.geolocation) {
      ret += "<input type=\"radio\" name=\"start_value\" id=\"geolocation\" value=\"geolocation\"><label for=\"geolocation\">"+t("start:geolocation")+"</label></br>";
    n++;
    }
    if(lastview=cookie_read("_osb_location")) {
      ret += "<input type=\"radio\" name=\"start_value\" id=\"lastview\" value=\"lastview\"><label for=\"lastview\">"+t("start:lastview")+"</label></br>";
    n++;
    }
    if(cookie_read("_osb_permalink")) {
      ret += "<input type=\"radio\" name=\"start_value\" id=\"savedview\" value=\"savedview\"><label for=\"savedview\">"+t("start:savedview")+"</label></br>";
    n++;
    }
    ret += "<input type=\"radio\" name=\"start_value\" id=\"startnormal\" value=\"startnormal\" checked><label for=\"startnormal\">"+t("start:startnormal")+"</label></br>";
    ret += "</br><input type=\"button\" name=\"start\" value=\"ok\" onclick=\"start_options()\"><input type=\"checkbox\" name=\"start_save\" id=\"save\" value=\"save\"><label for=\"save\">"+t("start:remember")+"</label></br></form>"; 

    document.getElementById("start").innerHTML=ret;
    details_style=document.getElementById("details").style;
    document.getElementById("start").style.display = "block"
    details_top = document.getElementById("start").offsetHeight + 180;
    details_style.top=details_top+"px";
    //timer = window.setInterval("start_slide(180,details_top,1)",10);

    //there is only one element in the list, so no necessarity to show it.
    if(n==0) {
      document.getElementById("start").style.display = "none";
    }

  } else {
    document.getElementById("start").style.display = "none";
    start_start(cookie_read("start_value"));
  }
}

function start_hide() {
  details_style=document.getElementById("details").style;
  document.getElementById("start").style.display = "none";
  timer = window.setInterval("start_slide(details_top,180,-1)",10);
}

function start_slide(from, to, direction) {
  var top = from+(direction*10*i);
  details_style.top=top+"px";
  if(direction==1) {
    if(top>=to) {
      window.clearInterval(timer);
      i=0;
      details_style.top=to+"px";
    }
  } else if(direction==-1) {
    if(top<=to) {
      window.clearInterval(timer);
      i=0;
      details_style.top=to+"px";
    }
  } else {
    window.clearInterval(timer); //just to prevent running timers on wrong direction-input
    i=0;
  }
  i++;
}

function start_options() {
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
  start_start(start_value);
  start_hide();
}

function start_start(start_value) {
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
