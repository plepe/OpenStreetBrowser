const turf = {
  booleanWithin: require('@turf/boolean-within').default
}

let data

register_hook('init_callback', function (initState, callback) {
  fetch('data/boundaries.geojson')
    .then(req => {
      if (req.status === 404) {
        throw (new Error('data/boundaries.geojson not found, run bin/download_dependencies'))
      }

      if (!req.ok) {
        throw (new Error('error loading data/boundaries.geojson: ' + req.statusText))
      }

      return req.json()
    })
    .then(body => {
      data = body.features
      global.setTimeout(() => callback(), 0)
    })
    .catch(err => global.setTimeout(() => callback(), 0))
})

function check (lat, lon) {
  // no data loaded
  if (!data) { return }

  const poi = {
    type: 'Point',
    coordinates: [ lon, lat ]
  }

  return data.filter(feature => turf.booleanWithin(poi, feature))
}

OverpassLayer.twig.extendFunction('boundaries', check)

register_hook('category-overpass-init', (category) => {
  category.layer.on('globalTwigData', (twigData) => {
    const center = category.layer.map.getCenter()
    const list = check(center.lat, center.lng)
    twigData.map.boundaries = list
    twigData.map.driving_side = 'right'

    if (list) {
      list.forEach(boundary => {
        if (boundary.tags.driving_side) {
          twigData.map.driving_side = boundary.tags.driving_side
        }
      })
    }
  })
})

module.exports = { check }
