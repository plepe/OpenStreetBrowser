var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')
var OpenStreetBrowserCategory = require('./src/OpenStreetBrowserCategory')

var map

window.onload = function() {
  map = L.map('map').setView([51.505, -0.09], 18)
  overpassFrontend = new OverpassFrontend('http://overpass.osm.rambler.ru/cgi/interpreter', {
    timeGap: 10,
    effortPerRequest: 100
  })

  var osm_mapnik = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }
  )
  osm_mapnik.addTo(map)

  var category = new OpenStreetBrowserCategory("gastro")
  category.addTo(map)
}
