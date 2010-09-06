var map_location_toolbox;

function map_location_options() {
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
  map_location_start(start_value);
  map_location_toolbox.deactivate();
}

function map_location_start(start_value) {
  switch (start_value) {
    case "geolocation":
      geo_init();
      break;
    case "lastview":
      set_location(lastview);
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

function map_location_activate() {
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
  text += "<input type=\"radio\" name=\"start_value\" id=\"startnormal\" value=\"startnormal\"><label for=\"startnormal\">"+t("start:startnormal")+"</label></br>";
  text += "</br><input type=\"button\" name=\"start\" value=\"ok\" onclick=\"map_location_options()\"><input type=\"checkbox\" name=\"start_save\" id=\"save\" value=\"save\"><label for=\"save\">"+t("start:remember")+"</label></br></form>";

  map_location_toolbox.content.innerHTML=text;

  var c=cookie_read('start_value');
  if(c) {
    document.getElementById(c).checked=true;
    document.getElementById('save').checked=true;
  } else {
    document.getElementById('startnormal').checked=true;
  }
}

function map_location_init() {
  map_location_toolbox=new toolbox({
    icon: "img/toolbox_map.png",
    icon_title: "map position",
    weight: -5,
    callback_activate: map_location_activate
  });
  register_toolbox(map_location_toolbox);

  if(((location.hash=="") || (location.hash=="#")) && (cookie_read("start_value")==null)) {
    map_location_toolbox.activate();
  }
  else {
    lastview=cookie_read("_osb_location");
    map_location_start(cookie_read("start_value"));
  }
}

register_hook("init", map_location_init);
