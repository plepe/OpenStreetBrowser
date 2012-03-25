function gps_follow_show(list) {
  var f=document.createElement("form");
  var i=document.createElement("input");
  i.type="checkbox";
  i.name="gps_follow";
  i.id="gps_follow";
  i.checked=options_get("gps_follow");
  f.appendChild(i);

  var i=dom_create_append(f, "label");
  dom_create_append_text(i, lang("gps_follow:label"));
  i.setAttribute("for", "gps_follow");

  list.push([ -5, f ]);
}

register_hook("gps_toolbox_show", gps_follow_show);
