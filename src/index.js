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
global.mainRepo = ''
global.tabs = null
global.rootCategories = {}

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

/* Geowiki Init */
let app
const baseModules = [
  require('./config'),
  require('./map'),
  require('./popups'),
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
  register_hook('options_form', form => app.emit('options-form', form))
  register_hook('options_orig_data', data => app.emit('options-orig-data', data))
  register_hook('options_save', (data, prevData) => app.emit('options-apply', data, prevData))
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
  app.emit('options-apply', options)

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

  if ('repo' in initState) {
    global.mainRepo = initState.repo
  }

  call_hooks('init')
  call_hooks_callback('init_callback', initState, onload2.bind(this, initState))
}

function onload2 (initState) {
  loadBaseCategory()

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

register_hook('state-apply', state => {
  if ('repo' in state && state.repo !== global.mainRepo && baseCategory) {
    baseCategory.remove()
    global.mainRepo = state.repo
    loadBaseCategory()
  }
})

register_hook('state-get', state => {
  if (global.mainRepo !== '') {
    state.repo = global.mainRepo
  }
})

//window.showRootContent = hide
