function layer_ol4pgm_category(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  this.ol4pgm = new ol4pgmLayer({
    url: id + ".py?x={x}&y={y}&z={z}&format=geojson-separate&tilesize=1024&srs=3857",
    maxZoom: 17,
    tileSize: 1024
  }, map);
  this.ol4pgm.onchange = this.write_div.bind(this);

  this.tags.set("name", "OSB");
  // shall_reload

  this.shall_reload = function(list, parent_div, viewbox) {
    var div=parent_div.child_divs[this.id];

    if(!div.open)
      return;

    this.write_div();
  }

  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;
    if(!div.open)
      return;

    dom_clean(div.data);

    show_list = this.ol4pgm.getFeaturesInExtent();
    for(var i=0; i<show_list.length; i++) {
      show_list[i] = new object_ol4pgm(show_list[i]);
    }

    new list(div.data, show_list, null, { });
  }
}

register_hook("init", function() {
  category_root.register_sub_category(new layer_ol4pgm_category("osb"));
});
