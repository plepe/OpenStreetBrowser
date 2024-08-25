/* globals map:true, overpassFrontend:true, currentPath:true, options:true, baseCategory:true, overpassUrl:true */

const tabs = require('modulekit-tabs')

import App from 'geowiki-lib-app'
var OverpassFrontend = require('overpass-frontend')
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var state = require('./state')
var hash = require('sheet-router/hash')
global.OpenStreetBrowserLoader = OpenStreetBrowserLoader

require('./CategoryIndex')
require('./CategoryOverpass')
require('./category.css')
const mapMetersPerPixel = require('./map-getMetersPerPixel')

global.map = null
global.baseCategory = null
global.overpassUrl = null
global.overpassFrontend = null
global.currentPath = null
global.mainRepo = ''
global.tabs = null
var lastPopupClose = 0

// Optional modules
require('./options')
require('./optionsYaml')
require('./language')
require('./overpassChooser')
require('./fullscreen')
require('./mapLayers')
require('./twigFunctions')
require('./markers')
require('./categories')
require('./wikipedia')
require('./image')
require('./moreCategories')
require('./addCategories')
require('./permalink')
//require('./leaflet-geo-search')
require('./nominatim-search')
require('./CategoryOverpassFilter')
require('./CategoryOverpassConfig')
require('./GeoInfo')
require('./PluginMeasure')
require('./PluginGeoLocate')
require('./tagsDisplay-tag2link')
require('./customCategory')
require('./pinnedCategories')
require('./boundaries')

const ObjectDisplay = require('./ObjectDisplay')
let currentObjectDisplay = null

/* Geowiki Init */
let app
const baseModules = []
App.modules = [...baseModules, ...App.modules, ...require('../modules')]

window.onload = function () {
  app = new App()
  app.on('init', init2)
}
/* /Geowiki Init */

function init2 (err) {
  var initState = config.defaultView

  if (global.location.search) {
    global.location = '.#' + global.location.search.substr(1) + (global.location.hash ? '&' + global.location.hash.substr(1) : '')
    return
  }

  map = L.map('map')
  map.getMetersPerPixel = mapMetersPerPixel.bind(map)

  map.attributionControl.setPrefix('<a target="_blank" href="https://wiki.openstreetmap.org/wiki/OpenStreetBrowser">OpenStreetBrowser</a>')

  // due to php export, options may be an array -> fix
  if (Array.isArray(options)) {
    options = {}
  }

  global.tabs = new tabs.Tabs(document.getElementById('globalTabs'))

  call_hooks('init')
  call_hooks_callback('init_callback', initState, onload2.bind(this, initState))

  map.createPane('selected')
  map.getPane('selected').style.zIndex = 498
  map.createPane('casing')
  map.getPane('casing').style.zIndex = 399
}

function onload2 (initState) {
  // Scale bar
  L.control.scale().addTo(map)

  if (!overpassUrl) {
    overpassUrl = config.overpassUrl
    if (Array.isArray(overpassUrl) && overpassUrl.length) {
      overpassUrl = overpassUrl[0]
    }
  }

  overpassFrontend = new OverpassFrontend(overpassUrl, config.overpassOptions)

  OpenStreetBrowserLoader.setMap(map)

  var newState
  if (location.hash && location.hash.length > 1) {
    newState = state.parse(location.hash.substr(1))
  } else {
    newState = initState
  }

  // make sure the map has an initial location
  if (!('zoom' in newState) && !('lat' in newState) && !('lon' in newState)) {
    state.apply(initState)
  }

  state.apply(newState)

  if ('repo' in newState) {
    global.mainRepo = newState.repo
  }

  loadBaseCategory()

  map.on('popupopen', function (e) {
    if (e.popup.object) {
      var url = e.popup.object.layer_id + '/' + (e.popup.object.sublayer_id === 'main' ? '' : e.popup.object.sublayer_id + ':') + e.popup.object.id
      if (location.hash.substr(1) !== url && location.hash.substr(1, url.length + 1) !== url + '/') {
        currentPath = url
        // only push state, when last popup close happened >1sec earlier
        state.update(null, Date.now() - lastPopupClose > 1000)
      }

      OpenStreetBrowserLoader.getCategory(e.popup.object.layer_id, function (err, category) {
        if (err) {
          alert(err)
          return
        }

        category.notifyPopupOpen(e.popup.object, e.popup)
      })
    }
  })
  map.on('popupclose', function (e) {
    if (e.popup.object) {
      OpenStreetBrowserLoader.getCategory(e.popup.object.layer_id, function (err, category) {
        if (err) {
          alert(err)
          return
        }

        category.notifyPopupClose(e.popup.object, e.popup)
      })
    }

    lastPopupClose = Date.now()
    currentPath = null
    state.update(null, true)
    hide()
  })
  map.on('moveend', function (e) {
    state.update()
  })

  hash(function (loc) {
    state.apply(state.parse(loc.substr(1)))
  })

  state.update()
  call_hooks('initFinish')
}

function loadBaseCategory () {
  let repo = global.mainRepo + (global.mainRepo === '' ? '' : '/')
  OpenStreetBrowserLoader.getCategory(repo + 'index', function (err, category) {
    if (err) {
      alert(err)
      return
    }

    baseCategory = category
    category.setParentDom(document.getElementById('contentListBaseCategory'))
    category.open()

    category.dom.classList.add('baseCategory')
  })
}

global.allMapFeatures = function (callback) {
  global.baseCategory.allMapFeatures(callback)
}

window.setPath = function (path, state) {
  currentPath = path

  if ('repo' in state && state.repo !== global.mainRepo && baseCategory) {
    baseCategory.remove()
    global.mainRepo = state.repo
    loadBaseCategory()
  }

  if (!path) {
    map.closePopup()
    return
  }

  var param = {
    showDetails: !!path.match(/\/details$/),
    hasLocation: 'lat' in state && 'lon' in state && 'zoom' in state
  }

  show(path, param, function (err) {
    if (err) {
      alert(err)
      return
    }

    call_hooks('show', path, param)
  })
}

function show (id, options, callback) {
  if (options.showDetails) {
    call_hooks('hide-' + document.getElementById('content').className)
    document.getElementById('content').className = 'details'
    document.getElementById('contentDetails').innerHTML = lang('loading')
  }

  var m = id.match(/^(.*)\/((?:[\w\d-]+:)?[nwr]\d+)(\/details)?$/)
  if (!m) {
    return callback(new Error('unknown request'))
  }

  var categoryId = m[1]
  var featureId = m[2]

  OpenStreetBrowserLoader.getCategory(categoryId, function (err, category) {
    if (err) {
      return callback(new Error('error loading category "' + categoryId + '": ' + err))
    }

    if (!category.parentDom) {
      category.setParentDom(document.getElementById('contentListAddCategories'))
    }

    category.show(
      featureId,
      options,
      function (err, data) {
        if (err) {
          return callback(new Error('error loading object "' + categoryId + '/' + featureId + '": ' + err))
        }

        if (!map._popup || map._popup !== data.popup) {
          data.feature.openPopup()
        }

        if (options.showDetails) {
          const dom = document.getElementById('contentDetails')
          currentObjectDisplay = new ObjectDisplay({
            feature: data,
            dom,
            category,
            displayId: 'details',
            fallbackIds: ['popup']
          }, err => callback(err))

          let closeButton = document.createElement('div')
          closeButton.setAttribute('data-order', -2000)
          closeButton.className = 'closeButton'
          closeButton.innerHTML = '×'
          closeButton.onclick = () => {
            hide()
          }
          dom.insertBefore(closeButton, dom.firstChild)
        } else {
          callback(err)
        }
      }
    )

    category.open()
  })
}

function hide () {
  if (currentObjectDisplay) {
    currentObjectDisplay.close()
    currentObjectDisplay = null
  }

  call_hooks('hide-' + document.getElementById('content').className)
  document.getElementById('content').className = 'list'
}

window.showRootContent = hide
