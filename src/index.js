var ipLocation = require('./ip-location')
var LeafletGeoSearch = require('leaflet-geosearch')

var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var hash = require('sheet-router/hash')
window.OpenStreetBrowserLoader = OpenStreetBrowserLoader

require('./CategoryIndex')
require('./CategoryOverpass')
var tagTranslations = require('./tagTranslations')

var map
var baseCategory

// Optional modules
require('./options')

window.onload = function() {
  map = L.map('map')

  call_hooks('init')
  call_hooks_callback('init_callback', onload2)
}

function onload2 () {
  ipLocation('', function (err, ipLoc) {
    if (typeof ipLoc === 'object' && 'latitude' in ipLoc) {
      map.setView([ ipLoc.latitude, ipLoc.longitude ], 14)
    } else {
      map.setView([ 51.505, -0.09 ], 14)
    }
  })

  // Add Geo Search
  var provider = new LeafletGeoSearch.OpenStreetMapProvider()
  var searchControl = new LeafletGeoSearch.GeoSearchControl({
    provider: provider,
    showMarker: false,
    retainZoomLevel: true
  })
  map.addControl(searchControl)

  // Geo location
  L.control.locate({
    keepCurrentZoomLevel: true,
    drawCircle: false,
    drawMarker: false,
    showPopup: false
  }).addTo(map);

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

  OpenStreetBrowserLoader.getCategory('index', function (err, category) {
    if (err) {
      alert(err)
      return
    }

    baseCategory = category
    category.setParentDom(document.getElementById('content'))
    category.open()
  })

  map.on('popupopen', function (e) {
    if (e.popup.object) {
      var url = e.popup.object.layer_id + '/' + e.popup.object.id
      if (location.hash !== url) {
        history.pushState(null, null, '#' + url)
      }
    }
  })
  map.on('popupclose', function (e) {
    history.pushState(null, null, '#')
    hide()
  })

  if (location.hash && location.hash.length > 1) {
    var url = location.hash.substr(1)

    options = {
      showDetails: !!location.hash.match(/\/details$/)
    }

    show(url, options, function () {})
  }

  hash(function (loc) {
    if (loc.length > 1) {
      var url = loc.substr(1)

      options = {
        showDetails: !!loc.match(/\/details$/)
      }

      show(url, options, function () {})
    }
  })

  tagTranslations.setTagLanguage(ui_lang)
  tagTranslations.load('node_modules/openstreetmap-tag-translations', ui_lang, function (err) {
    if (err) {
      alert('Error loading translations: ' + err)
      return
    }
  })
}

function show (id, options, callback) {
  if (options.showDetails) {
    document.getElementById('content').innerHTML = 'Loading ...'
  }

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

    if (!category.parentDom) {
      category.setParentDom(document.getElementById('content'))
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

        data.feature.openPopup()

        if (options.showDetails) {
          showDetails(data, category)
        }

        callback(err)
      }
    )

    category.open()
  })
}

function showDetails (data, category) {
  var dom = document.getElementById('content')

  dom.innerHTML = ''

  var div = document.createElement('h1')
  div.className = 'title'
  div.innerHTML = data.data.title
  dom.appendChild(div)

  var div = document.createElement('div')
  div.className = 'body'
  div.innerHTML = data.data.body
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

function hide () {
  var content = document.getElementById('content')
  content.innerHTML = ''

  if (baseCategory) {
    baseCategory.setParentDom(content)
  }
}
window.showRootContent = hide
