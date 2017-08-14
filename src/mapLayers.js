var mapLayers = {}

register_hook('init', function () {
  if (!config.baseMaps) {
    var osm_mapnik = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      {
        maxZoom: config.maxZoom || 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }
    )
    osm_mapnik.addTo(map)

    return
  }

  var layers = {}
  var firstLayer = null
  for (var i = 0; i < config.baseMaps.length; i++) {
    var def = config.baseMaps[i]

    var layer = L.tileLayer(
      def.url,
      {
        attribution: def.attribution,
        maxNativeZoom: def.maxZoom,
        maxZoom: config.maxZoom || 19
      }
    )

    if (firstLayer === null) {
      firstLayer = layer
    }

    layers[def.name] = layer
    mapLayers[def.id] = layer
  }

  firstLayer.addTo(map)
  L.control.layers(layers).addTo(map)
})
