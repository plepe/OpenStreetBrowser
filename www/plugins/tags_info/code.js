function tags_info_show(disp, tags) {
  // show an additional tag
  disp.foo="bar";

  // hide a tag
  delete(disp['#geo']);

  // change string of tag
  disp.amenity="="+tags.get("amenity")+"=";

  // convert tag to dom
  var b=document.createElement("b");
  dom_create_append_text(b, tags.get("name"));
  disp.name=b;
}

register_hook("info_tags_show", tags_info_show);
