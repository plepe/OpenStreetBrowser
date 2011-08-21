var map_key_zoom=-1;
var map_key_request=0;
var map_key_list={};
var map_key_shown=[];

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
  var ret=response.return_value;

  text=weight_sort(ret.list);

  var map_key=document.getElementById("map_key");
  map_key.innerHTML=
    lang("map_key:head")+" ("+lang("map_key:zoom")+" "+ret.param.zoom+")"+
    text.join("<br>\n");

  map_key_zoom=ret.param.zoom;
  map_key_shown=ret.param.entries;
}

function map_key_check() {
  var map_key=document.getElementById("map_key");

  if(map_key_request)
    map_key_request.abort();

  if(map_key.className=='map_key') {
    // list of visible entries
    var list=[];
    for(var i in map_key_list) {
      if(map_key_list[i].visibility())
	list.push(i);
    }

    // only load new map key if either the zoom level changed or different
    // entries needs to be shown
    if((map_key_zoom!=map.zoom)||(map_key_shown.join(",")!=list.join(","))) {
      // send request for map key info
      map_key_request=ajax("map_key", { "zoom": map.zoom, "entries": list },
        map_key_display);
    }
  }
}

function map_key_init() {
}

register_hook("view_changed", map_key_check);
register_hook("overlays_visibility_change", map_key_check);
