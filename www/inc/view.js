var click_override=null;

function view_call_back(response) {
  var data=response.responseXML;
  category_request=null;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var details=document.getElementById("details");
  details.className="info";
  dom_clean(details);

  var ul=dom_create_append(details, "ul");

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

  var details=document.getElementById("details");
  details.innerHTML="Loading";

  call_hooks("view_click");
}
