var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')
var OpenStreetBrowserLoader = require('./src/OpenStreetBrowserLoader')
window.OpenStreetBrowserLoader = OpenStreetBrowserLoader

require('./src/OpenStreetBrowserCategory')
require('./src/OpenStreetBrowserIndex')
var tagTranslations = require('./src/tagTranslations')

var map

window.onload = function() {
  map = L.map('map').setView([51.505, -0.09], 18)
  overpassFrontend = new OverpassFrontend('//overpass-api.de/api/interpreter', {
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

  OpenStreetBrowserLoader.setMap(map)
  OpenStreetBrowserLoader.setParentDom(document.getElementById('info'))

  OpenStreetBrowserLoader.getCategory('index', function (err, category) {
    if (err) {
      alert(err)
      return
    }

    category.setParentDom(document.getElementById('info'))
    category.open()
  })

  show('gastro/n281657531', function () {})

  tagTranslations.load('node_modules/openstreetmap-tag-translations', 'de', function (err) {
    if (err) {
      alert('Error loading translations: ' + err)
      return
    }

    console.log(tagTranslations.trans('amenity'))
    console.log(tagTranslations.trans('amenity', undefined))
    console.log(tagTranslations.trans('amenity', 'restaurant'))
    console.log(tagTranslations.trans('amenity', 'restaurant', 5))
  })
}

function show (id, callback) {
  document.getElementById('info').style.display = 'none'
  document.getElementById('object').style.display = 'block'
  document.getElementById('object').innerHTML = 'Loading ...'

  id = id.split('/')

  if (id.length < 2) {
    alert('unknown request')
    return
  }

  OpenStreetBrowserLoader.getCategory(id[0], function (err, category) {
    if (err) {
      alert('error loading category "' + id[0] + '": ' + err)
      return
    }

    category.show(
      id[1],
      {
      },
      function (err, data) {
        if (err) {
          alert('error loading object "' + id[0] + '/' + id[1] +'": ' + err)
          return
        }

        show1(data, category, callback)

        callback(err)
      }
    )

    category.open()
  })
}

function show1 (data, category) {
  data.feature.openPopup()

  var dom = document.getElementById('object')

  dom.innerHTML = ''

  var div = document.createElement('h1')
  div.className = 'title'
  div.innerHTML = data.data.featureTitle
  dom.appendChild(div)

  var div = document.createElement('div')
  div.className = 'body'
  div.innerHTML = data.data.featureBody
  dom.appendChild(div)

  var h = document.createElement('h3')
  h.innerHTML = 'Attributes'
  dom.appendChild(h)

  var div = document.createElement('dl')
  div.className = 'tags'
  for (var k in data.object.tags) {
    var dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)
    var dd = document.createElement('dd')
    dd.appendChild(document.createTextNode(data.object.tags[k]))
    div.appendChild(dd)
  }
  dom.appendChild(div)

  var h = document.createElement('h3')
  h.innerHTML = 'OSM Meta'
  dom.appendChild(h)

  var div = document.createElement('dl')
  div.className = 'meta'
  var dt = document.createElement('dt')
  dt.appendChild(document.createTextNode('id'))
  div.appendChild(dt)
  var dd = document.createElement('dd')
  var a = document.createElement('a')
  a.appendChild(document.createTextNode(data.object.type + '/' + data.object.osm_id))
  a.href = 'https://openstreetmap.org/' + data.object.type + '/' + data.object.osm_id
  a.target = '_blank'

  dd.appendChild(a)
  div.appendChild(dd)
  for (var k in data.object.meta) {
    var dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)
    var dd = document.createElement('dd')
    dd.appendChild(document.createTextNode(data.object.meta[k]))
    div.appendChild(dd)
  }
  dom.appendChild(div)
}
