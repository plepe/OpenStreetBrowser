var click_override=null;

function view_call_back(response) {
  var data=response.responseXML;
  var map_div=document.getElementById("map");
  category_request=null;
  map_div.style.cursor="default";

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var text_node=data.getElementsByTagName("text");

  if(text_node&&text_node[0]) {
    var t=get_content(text_node[0]);
    if(t.substr(0, 8)=="redirect") {
      location.hash="#"+t.substr(9);
      highlight_next_no_zoom=true;
      return;
    }
  }

  var info_content=document.getElementById("details_content");
  var info=document.getElementById("details");

  info.className="info";
//  map_div.className="map";
  if(text_node) {
    if(!text_node[0])
      show_msg("Returned data invalid", response.responseText);
    var text=get_content(text_node[0]);
    info_content.innerHTML=text;
  }

  check_overlays(data);

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);

  return;
}

function view_click(event) {
  var pos=map.getLonLatFromPixel(event.xy);
  first_load=0;

  call_hooks("view_click", event);

  if(click_override) {
    click_override(event, pos);
    return;
  }

  var now=new Date().getTime();
  if(now<view_changed_last+500)
    return;

  view_changed_last=now;

  var map_div=document.getElementById("map");
  map_div.style.cursor="progress";

//  if(get_hash()!="")
//    return 0;

  if(view_changed_timer)
    clearTimeout(view_changed_timer);

  view_changed_timer=setTimeout("view_click_delay("+pos.lon+", "+pos.lat+")", 200);
}

function view_click_delay(lon, lat) {
  last_location_hash="#";
  location.hash="#";

  var x=map.calculateBounds();

  if(category_request) {
    category_request.abort();
  }

  category_request=ajax("find_objects", { "left": x.left, "top": x.top, "right": x.right, "bottom": x.bottom, "zoom": map.zoom, "lon": lon, "lat": lat, "overlays": list_overlays() }, view_call_back);
}


