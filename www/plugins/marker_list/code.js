function marker_list_category(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  // shall_reload
  this.shall_reload=function(list, parent_div, viewbox) {
    var div=parent_div.child_divs[this.id];

    if(!div.open)
      return;

    this.write_div();
  }

  // write_div
  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;
    if(!div.open)
      return;

    dom_clean(div.data);

    var show_list=[];
    var bounds=map.calculateBounds().toGeometry();
    for(var i=0; i<marker_list.length; i++) {
      var marker=marker_list[i];
      if(bounds.intersects(marker.feature.geometry)) {
        show_list.push(marker.write_list());
      }
    }

    new list(div.data, show_list, null, { show_count: 2, empty_text: lang("marker_list:empty_text") });
  }
}

function marker_list_init() {
  category_root.register_sub_category(new marker_list_category(lang("marker_list:title")));
}

register_hook("init", marker_list_init);
