var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')
var OpenStreetBrowserLoader = require('./src/OpenStreetBrowserLoader')

var map
var categories = {}

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


  OpenStreetBrowserLoader('index', function (err, category) {
    categories[category.id] = category
    category.addTo(map, document.getElementById('info'))
  })
}

window.toggleCategory = function (id) {
  OpenStreetBrowserLoader(id, function (err, category) {
    categories[category.id] = category
    category.addTo(map, document.getElementById('category-' + id).lastChild)
  })
}
