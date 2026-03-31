/* globals map:true, overpassFrontend:true, currentPath:true, options:true, baseCategory:true, overpassUrl:true */

const tabs = require('modulekit-tabs')
const async = require('async')

import App from '@geowiki-net/geowiki-lib-app'

var OverpassFrontend = require('@geowiki-net/geowiki-api')
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var hash = require('sheet-router/hash')
global.OpenStreetBrowserLoader = OpenStreetBrowserLoader

require('./CategoryIndex')
require('./CategoryOverpass')
require('./category.css')

global.map = null
global.baseCategory = null
global.overpassUrl = null
global.overpassFrontend = null
global.geowikiAPI = null
global.currentPath = null
global.mainRepo = ''
global.tabs = null
global.rootCategories = {}
var lastPopupClose = 0

// Optional modules
require('./options')
require('./optionsYaml')
require('./language')
require('./overpassChooser')
require('./fullscreen')
require('./zenMode')
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
const baseModules = [
  require('./config'),
  require('./map'),
]
App.modules = [...baseModules, ...App.modules, ...require('../modules')]

window.onload = function () {
  app = new App()
  global.app = app

  app.config = config

  app.initModules(init2)

  // legacy
  app.state.on('apply', state => call_hooks('state-apply', state))
  app.state.on('get', state => call_hooks('state-get', state))
}

/* /Geowiki Init */

function init2 (err) {
  if (global.location.search) {
    global.location = '.#' + global.location.search.substr(1) + (global.location.hash ? '&' + global.location.hash.substr(1) : '')
    return
  }

  app.loadCssFiles()

  app.config.defaultState = config.defaultView
  var initState = app.getInitState()

  // due to php export, options may be an array -> fix
  if (Array.isArray(options)) {
    options = {}
  }

  global.tabs = new tabs.Tabs(document.getElementById('globalTabs'))

  if (!overpassUrl) {
    overpassUrl = config.overpassUrl
    if (Array.isArray(overpassUrl) && overpassUrl.length) {
      overpassUrl = overpassUrl[0]
    }
  }

  overpassFrontend = new OverpassFrontend(overpassUrl, config.overpassOptions)
  geowikiAPI = overpassFrontend

  app.init(initState)
  call_hooks('init')
  call_hooks_callback('init_callback', initState, onload2.bind(this, initState))
}

function onload2 (initState) {

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
        console.log('TODO state update')
        //state.update(null, Date.now() - lastPopupClose > 1000)
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
    app.state.updateLink({ update: true })
    hide()
  })
  map.on('moveend', function (e) {
    app.state.updateLink()
  })

  app.state.updateLink()
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
    global.rootCategories[repo + 'index'] = category
    category.setParentDom(document.getElementById('contentListBaseCategory'))
    category.open()

    category.dom.classList.add('baseCategory')
  })
}

global.allMapFeatures = function (callback) {
  let result = []

  async.eachOf(global.rootCategories, (baseCategory, id, done) => {
    if (!baseCategory.allMapFeatures) {
      console.error('allMapFeatures(): Root-Category ' + id + ': allMapFeatures() not supported')
      return done()
    }

    baseCategory.allMapFeatures(
      (err, data) => {
        if (err) {
          console.error('allMapFeatures(): Repo ' + id + ': error loading allMapFeatures', err)
          return done()
        }

        result = result.concat(data)
        done()
      }
    )
  },
  (err) => {
    callback(err, result)
  })
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
