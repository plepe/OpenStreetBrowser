const hooks = require('modulekit-hooks')
const LeafletGeoSearch = require('leaflet-geosearch')

hooks.register('init', function () {
  // Add Geo Search
  var provider = new LeafletGeoSearch.OpenStreetMapProvider()
  var searchControl = new LeafletGeoSearch.GeoSearchControl({
    provider: provider,
    showMarker: false,
    retainZoomLevel: true
  })
  global.map.addControl(searchControl)
})
