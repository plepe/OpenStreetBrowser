var mapkey_zoom=-1;
var mapkey_request=0;
var mapkey_overlays=[];

function toggle_mapkey() {
  var map=document.getElementById("map");
  var map_key=document.getElementById("map_key");

  if(map_key.className=='map_key_hidden') {
    map.className='map_with_mapkey';
    map_key.className='map_key';
    check_mapkey();
  }
  else {
    map.className='map';
    map_key.className='map_key_hidden';
  }
}

function display_mapkey(response) {
  mapkey_request=0;
  var map_key=document.getElementById("map_key");
  var data=response.responseXML;

  var text_node=data.getElementsByTagName("text");

  if(text_node) {
    if(!text_node[0])
      show_msg("Returned data invalid", response.responseText);
    var text=get_content(text_node[0]);
    map_key.innerHTML=text;
  }

  var zoom=data.getElementsByTagName("zoom");
  mapkey_zoom=zoom[0].getAttribute("value");

  mapkey_overlays=[];
  var overlays=data.getElementsByTagName("overlay");
  for(var i=0; i<overlays.length; i++) {
    mapkey_overlays[overlays[i].getAttribute("value")]=1;
  }

  check_mapkey();
}

function keys(ob) {
  var ret=[];

  for(var i in ob) {
    ret.push(i);
  }

  return ret;
}

function check_mapkey() {
  var map_key=document.getElementById("map_key");
  var new_mapkey_overlays=[];
  var overlays_changed=0;

  if(mapkey_request)
    return;

  for(var i in overlays_layers) {
    if(overlays_layers[i].visibility==true) {
      new_mapkey_overlays[i]=1;
      if(!mapkey_overlays[i])
	overlays_changed=1;
    }
    else {
      if(mapkey_overlays[i])
	overlays_changed=1;
    }
  }
//  alert(keys(mapkey_overlays)+" = "+keys(new_mapkey_overlays)+" = "+overlays_changed);

  if(map_key.className=='map_key') {
    if((mapkey_zoom!=map.zoom)||(overlays_changed)) {
      mapkey_request=1;
      ajax("get_mapkey", { "zoom": map.zoom, "overlays": new_mapkey_overlays }, display_mapkey);
    }
  }
}

function map_key_init() {
  for(var i in overlays_layers) {
    overlays_layers[i].events.register("visibilitychanged", map, check_mapkey);
  }
}
