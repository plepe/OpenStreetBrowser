var mapkey_zoom=-1;
var mapkey_request=0;
var mapkey_overlays=[];
var map_key_list={};

function map_key_entry(id) {
  this.visibility=function() {
    return false;
  }

  // constructor
  this.type="map_key_entry";
  this.id=id;
  map_key_list[id]=this;
}

function map_key_remove(id) {
  delete(map_key_list[id]);
}

function map_key_toggle() {
  var map=document.getElementById("map");
  var map_key=document.getElementById("map_key");

  if(map_key.className=='map_key_hidden') {
    map.className='map_with_mapkey';
    map_key.className='map_key';
    map_key_check();
  }
  else {
    map.className='map';
    map_key.className='map_key_hidden';
  }
}

function map_key_display(response) {
  mapkey_request=0;
  var ret=response.return_value;

  text=weight_sort(ret.list);

  var map_key=document.getElementById("map_key");
  map_key.innerHTML=
    lang("map_key:head")+" ("+lang("map_key:zoom")+" "+ret.param.zoom+")"+
    text.join("<br>\n");

  var zoom=data.getElementsByTagName("zoom");
  mapkey_zoom=zoom[0].getAttribute("value");

  mapkey_overlays=[];
  var overlays=data.getElementsByTagName("overlay");
  for(var i=0; i<overlays.length; i++) {
    mapkey_overlays[overlays[i].getAttribute("value")]=1;
  }

  map_key_check();
}

function map_key_check() {
  var map_key=document.getElementById("map_key");
  var overlays_changed=0;

  if(mapkey_request)
    return;

  if(map_key.className=='map_key') {
    if((mapkey_zoom!=map.zoom)||(overlays_changed)) {
      // list of visible entries
      var list=[];
      for(var i in map_key_list) {
	if(map_key_list[i].visibility())
	  list.push(i);
      }

      // send request for map key info
      mapkey_request=1;
      ajax("map_key", { "zoom": map.zoom, "entries": list }, map_key_display);
    }
  }
}

function map_key_init() {
}

register_hook("view_changed", map_key_check);
