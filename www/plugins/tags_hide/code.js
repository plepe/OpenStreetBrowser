/*
function tags_info_show_EXAMPLE(disp, tags) {
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
*/

function tags_hide_show(disp, tags) {
  // Hide all #geo-tags
  for(var i in disp) {
    if((i=="#geo")||(i.match(/^#geo:/))) {
      delete(disp[i]);
    }
  }
}

register_hook("info_tags_show", tags_hide_show);
