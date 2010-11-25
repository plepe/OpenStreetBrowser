function tags_info_show(disp) {
  // show an additional tag
  disp.set("foo", "bar");

  // hide a tag
  disp.erase("#geo");

  // change string of tag
  disp.set("amenity", "="+disp.get("amenity")+"=");
}

register_hook("info_tags_show", tags_info_show);
