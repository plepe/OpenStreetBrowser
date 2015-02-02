function layer_ol4pgm_category(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  this.ol4pgm = new ol4pgmLayer({
    url: id + ".py?x={x}&y={y}&z={z}&format=geojson-separate&tilesize=1024&srs=3857",
    maxZoom: 17,
    tileSize: 1024
  }, map);

  this.tags.set("name", "OSB");
}

register_hook("init", function() {
  category_root.register_sub_category(new layer_ol4pgm_category("osb"));
});
