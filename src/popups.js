const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

module.exports = {
  id: 'popups',
  appInit
}

let app
let currentPath
var lastPopupClose = 0
let currentObjectDisplay = null

function appInit (_app) {
  app = _app

  app.on('state-get', state => {
    state.path = currentPath
  })

  app.on('state-apply', state => {
    currentPath = state.path

    if (!state.path) {
      map.closePopup()
      return
    }

    var param = {
      showDetails: !!currentPath.match(/\/details$/),
      hasLocation: 'lat' in state && 'lon' in state && 'zoom' in state
    }

    show(currentPath, param, function (err) {
      if (err) {
        alert(err)
        return
      }

      call_hooks('show', currentPath, param)
    })
  })

  app.on('map-init', mapInit)
}

function mapInit () {
  app.map.on('popupopen', function (e) {
    if (e.popup.object) {
      var url = e.popup.object.layer_id + '/' + (e.popup.object.sublayer_id === 'main' ? '' : e.popup.object.sublayer_id + ':') + e.popup.object.id
      if (location.hash.substr(1) !== url && location.hash.substr(1, url.length + 1) !== url + '/') {
        currentPath = url
        // only push state, when last popup close happened >1sec earlier
        app.state.updateLink(null, Date.now() - lastPopupClose > 1000)
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

  app.map.on('popupclose', function (e) {
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


