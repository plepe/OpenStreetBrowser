var map_key; // div for the map key
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

  if(!has_css_class(map_key, 'shown')) {
    add_css_class(map, 'with_map_key');
    add_css_class(map_key, 'shown');
    map_key_check();
  }
  else {
    del_css_class(map, 'with_map_key');
    del_css_class(map_key, 'shown');
  }
}

function map_key_format(map_key_def) {
  if(typeof map_key_def=="string") {
    var div=document.createElement("div");
    div.innerHTML=map_key_def;
    return div;
  }

  if(is_dom(map_key_def)) {
    return map_key_def;
  }
}

function map_key_display(response) {
  var ret=response.return_value;

  call_hooks("map_key", ret.list, ret.param.zoom, ret.param.entries);

  text=weight_sort(ret.list);

  map_key.innerHTML=
    lang("map_key:name")+" ("+lang("zoom")+" "+ret.param.zoom+")";

  for(var i=0; i<text.length; i++) {
    var div=map_key_format(text[i]);
    map_key.appendChild(div);
  }

  map_key_zoom=ret.param.zoom;
  map_key_shown=ret.param.entries;
}

function map_key_check() {
  if(map_key_request)
    map_key_request.abort();

  if(has_css_class(map_key, 'shown')) {
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
  map_key=dom_create_append(document.body, "div");
  map_key.id="map_key";
}

register_hook("view_changed", map_key_check);
register_hook("overlays_visibility_change", map_key_check);
register_hook("init", map_key_init);
