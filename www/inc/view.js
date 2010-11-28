var click_override=null;

function view_click(event) {
  var pos=map.getLonLatFromPixel(event.xy);
  first_load=0;

  call_hooks("view_click", event, pos);

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
//  call_hooks("view_click", lon, lat);
}
