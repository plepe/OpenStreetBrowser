var mapLayers = {}
var currentMapLayer = null

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
  var preferredLayer = null
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

    if (preferredLayer === null) {
      preferredLayer = layer
    }
    if (def.id === options.preferredBaseMap) {
      preferredLayer = layer
    }

    layers[def.name] = layer
    mapLayers[def.id] = layer
  }

  preferredLayer.addTo(map)
  L.control.layers(layers).addTo(map)

  map.on('baselayerchange', function (e) {
    currentMapLayer = e.layer
  })
})

register_hook('options_form', function (def) {
  var baseMaps = {}

  if (!config.baseMaps) {
    return
  }

  for (var i = 0; i < config.baseMaps.length; i++) {
    baseMaps[config.baseMaps[i].id] = config.baseMaps[i].name
  }

  def.preferredBaseMap = {
    'name': lang('options:preferredBaseMap'),
    'type': 'select',
    'values': baseMaps
  }
})

register_hook('options_save', function (data) {
  if ('preferredBaseMap' in data && data.preferredBaseMap in mapLayers) {
    if (currentMapLayer) {
      map.removeLayer(currentMapLayer)
    }

    map.addLayer(mapLayers[data.preferredBaseMap])
  }
})
