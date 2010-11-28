var click_override=null;
var view_div;

function view_call_back(response) {
  var data=response.responseXML;
  category_request=null;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  dom_clean(view_div);
  var ul=dom_create_append(view_div, "ul");

  var result=data.getElementsByTagName("list");
  if(!result.length) {
    alert("No result!");
    return;
  }
  result=result[0];

  var match=result.firstChild;
  while(match) {
    var li=dom_create_append(ul, "li");
    var osm=new osm_object(match);
    dom_create_append_text(li, osm.name());

    match=match.nextSibling;
  }

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

  if(view_changed_timer)
    clearTimeout(view_changed_timer);

  view_changed_timer=setTimeout("view_click_delay("+pos.lon+", "+pos.lat+")", 200);
}

function view_click_delay(lon, lat) {
  last_location_hash="#";
  location.hash="#";

  if(category_request) {
    category_request.abort();
  }

  var cat=category_root.visible_list();
  cat=category_list_to_string(cat);

  category_request=ajax("find_objects", { "zoom": map.zoom, "lon": lon, "lat": lat, "categories": cat }, view_call_back);
  var lonlat = new OpenLayers.LonLat(lon, lat).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

  var details=document.getElementById("details_content");
  dom_clean(details);

  var div=dom_create_append(details, "div");
  div.className="object";

  var h1=dom_create_append(details, "h1");
  dom_create_append_text(h1, lang("Position: ")+lonlat.lat.toFixed(5)+"/"+lonlat.lon.toFixed(5));

  var actions=dom_create_append(details, "div");
  var a=dom_create_append(actions, "a");
  a.className="zoom";
  a.href="#";
  a.onclick=redraw;
  dom_create_append_text(a, lang("info_back"));

  var h2=dom_create_append(details, "h2");
  dom_create_append_text(h2, lang("head:whats_here"));

  view_div=dom_create_append(details, "div");
  view_div.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

  call_hooks("view_click");
}
