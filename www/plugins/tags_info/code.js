function tags_info_show(disp) {
  // show an additional tag
  disp.set("foo", "bar");

  // hide a tag
  disp.erase("#geo");

  // change string of tag
  disp.set("amenity", "="+disp.get("amenity")+"=");

  // convert tag to dom
  var b=document.createElement("b");
  dom_create_append_text(b, disp.get("name"));
  disp.set("name", b);
}

register_hook("info_tags_show", tags_info_show);
