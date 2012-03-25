var gps_follow_active=false;
var gps_follow_input;

function gps_follow_update(ob) {
  if(gps_follow_active) {
    var pos = gps_object.get_pos();
    pos=new clone(pos);
    pos.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());

    map.setCenter(pos);
  }
}

function gps_follow_toggle() {
  options_set("gps_follow", json_encode(gps_follow_input.checked));
  gps_follow_active=gps_follow_input.checked;

  if(gps_follow_active) {
    var pos;
    if(geo_object&&(pos=gps_object.get_pos()))
      pos=new clone(pos);
      pos.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());

      map.setCenter(pos);
  }
}

function gps_follow_show(list) {
  var f=document.createElement("form");
  var i=document.createElement("input");
  i.type="checkbox";
  i.name="gps_follow";
  i.id="gps_follow";
  i.checked=json_decode(options_get("gps_follow"));
  i.onchange=gps_follow_toggle;
  gps_follow_input=i;
  f.appendChild(i);

  var i=dom_create_append(f, "label");
  dom_create_append_text(i, lang("gps_follow:label"));
  i.setAttribute("for", "gps_follow");

  list.push([ -5, f ]);
}

function gps_follow_init() {
  gps_follow_active=json_decode(options_get("gps_follow"));
}

register_hook("gps_toolbox_show", gps_follow_show);
register_hook("gps_update", gps_follow_update);
register_hook("init", gps_follow_init);
