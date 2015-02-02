register_hook("init", function() {
  new ol4pgmLayer({
    url: "osb.py?x={x}&y={y}&z={z}&format=geojson-separate&tilesize=1024&srs=3857",
    maxZoom: 17,
    tileSize: 1024
  }, map);
});
