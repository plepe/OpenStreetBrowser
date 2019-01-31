const LeafletGeoSearch = require('leaflet-geosearch')

register_hook('init', function () {
  // Add Geo Search
  var provider = new LeafletGeoSearch.OpenStreetMapProvider()
  var searchControl = new LeafletGeoSearch.GeoSearchControl({
    provider: provider,
    showMarker: false,
    retainZoomLevel: true
  })
  global.map.addControl(searchControl)
})
