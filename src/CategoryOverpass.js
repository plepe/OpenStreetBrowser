/* global showDetails, openstreetbrowserPrefix */
/* eslint camelcase: 0 */
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var CategoryBase = require('./CategoryBase')
var state = require('./state')
var tabs = require('modulekit-tabs')
var markers = require('./markers')
var maki = require('./maki')
var qs = require('sheet-router/qs')

var defaultValues = {
  feature: {
    title: "{{ localizedTag(tags, 'name') |default(localizedTag(tags, 'operator')) | default(localizedTag(tags, 'ref')) | default(trans('unnamed')) }}",
    markerSign: '',
    'style:selected': {
      color: '#3f3f3f',
      width: 3,
      opacity: 1,
      radius: 12,
      pane: 'selected'
    },
    markerSymbol: '{{ markerPointer({})|raw }}',
    listMarkerSymbol: '{{ markerCircle({})|raw }}',
    preferredZoom: 16
  },
  queryOptions: {
  }
}

CategoryOverpass.prototype = Object.create(CategoryBase.prototype)
CategoryOverpass.prototype.constructor = CategoryOverpass
function CategoryOverpass (options, data) {
  var p

  CategoryBase.call(this, options, data)

  data.id = this.id

  // set undefined data properties from defaultValues
  for (var k1 in defaultValues) {
    if (!(k1 in data)) {
      data[k1] = JSON.parse(JSON.stringify(defaultValues[k1]))
    } else if (typeof defaultValues[k1] === 'object') {
      for (var k2 in defaultValues[k1]) {
        if (!(k2 in data[k1])) {
          data[k1][k2] = JSON.parse(JSON.stringify(defaultValues[k1][k2]))
        } else if (typeof defaultValues[k1][k2] === 'object') {
          for (var k3 in defaultValues[k1][k2]) {
            if (!(k3 in data[k1][k2])) {
              data[k1][k2][k3] = JSON.parse(JSON.stringify(defaultValues[k1][k2][k3]))
            }
          }
        }
      }
    }
  }

  // get minZoom
  if ('minZoom' in data) {
    // has minZoom
  } else if (typeof data.query === 'object') {
    data.minZoom = Object.keys(data.query)[0]
  } else {
    data.minZoom = 14
  }

  data.feature.appUrl = '#' + this.id + '/{{ id }}'
  data.styleNoBindPopup = [ 'selected' ]
  data.stylesNoAutoShow = [ 'selected' ]
  data.updateAssets = this.updateAssets.bind(this)

  this.layer = new OverpassLayer(data)

  this.layer.onLoadStart = function (ev) {
    this.dom.classList.add('loading')
    if (this.parentCategory) {
      this.parentCategory.notifyChildLoadStart(this)
    }
  }.bind(this)
  this.layer.onLoadEnd = function (ev) {
    this.dom.classList.remove('loading')
    if (this.parentCategory) {
      this.parentCategory.notifyChildLoadEnd(this)
    }

    if (ev.error) {
      alert('Error loading data from Overpass API: ' + ev.error)
    }
  }.bind(this)
  this.layer.on('update', function (object, ob) {
    if (!ob.popup || !ob.popup._contentNode || map._popup !== ob.popup) {
      return
    }

    this.updatePopupContent(ob, ob.popup)

    if (document.getElementById('content').className === 'details open') {
      showDetails(ob, this)
    }
  }.bind(this))

  p = document.createElement('div')
  p.className = 'loadingIndicator'
  p.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">' + lang('loading') + '</span>'
  this.dom.appendChild(p)

  this.domStatus = document.createElement('div')
  this.domStatus.className = 'status'

  if (this.data.lists) {
    this.dom.insertBefore(this.domStatus, this.domHeader.nextSibling)
  } else {
    p = document.createElement('div')
    p.className = 'loadingIndicator2'
    p.innerHTML = '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>'
    this.dom.appendChild(p)

    this.dom.appendChild(this.domStatus)
  }

  register_hook('state-get', function (state) {
    if (this.isOpen) {
      if (state.categories) {
        state.categories += ','
      } else {
        state.categories = ''
      }

      state.categories += this.id
    }
  }.bind(this))

  register_hook('state-apply', function (state) {
    if (!('categories' in state)) {
      return
    }

    var list = state.categories.split(',')
    if (list.indexOf(this.id) === -1) {
      this.close()
    }

    // opening categories is handled by src/categories.js
  }.bind(this))
}

CategoryOverpass.prototype.updateAssets = function (div) {
  var imgs = div.getElementsByTagName('img')
  for (var i = 0; i < imgs.length; i++) {
    let img = imgs[i]

    // TODO: 'src' is deprecated, use only data-src
    var src = img.getAttribute('src') || img.getAttribute('data-src')
    if (src === null) {
    } else if (src.match(/^maki:.*/)) {
      let m = src.match(/^maki:([a-z0-9-]*)(?:\?(.*))?$/)
      if (m) {
        let span = document.createElement('span')
        img.parentNode.insertBefore(span, img)
        img.parentNode.removeChild(img)
        i--
        maki(m[1], m[2] ? qs(m[2]) : {}, function (err, result) {
          if (err === null) {
            span.innerHTML = result
          }
        })
      }
    } else if (!src.match(/^(https?:|data:|\.|\/)/)) {
      img.setAttribute('src', (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
      'asset.php?repo=' + this.options.repositoryId + '&file=' + encodeURIComponent(img.getAttribute('src')))
    }
  }
}

CategoryOverpass.prototype.load = function (callback) {
  OpenStreetBrowserLoader.getTemplate('popupBody', this.options, function (err, template) {
    if (err) {
      console.log("can't load popupBody.html")
    } else {
      this.popupBodyTemplate = template
    }

    callback(null)
  }.bind(this))
}

CategoryOverpass.prototype.setMap = function (map) {
  CategoryBase.prototype.setMap.call(this, map)

  this.map.on('zoomend', function () {
    this.updateStatus()
    this.updateInfo()
  }.bind(this))

  this.updateStatus()
  this.updateInfo()
}

CategoryOverpass.prototype.updateStatus = function () {
  this.domStatus.innerHTML = ''

  if (typeof this.data.query === 'object') {
    var highestZoom = Object.keys(this.data.query).reverse()[0]
    if (this.map.getZoom() < highestZoom) {
      this.domStatus.innerHTML = lang('zoom_in_more')
    }
  }

  if ('minZoom' in this.data && this.map.getZoom() < this.data.minZoom) {
    this.domStatus.innerHTML = lang('zoom_in_appear')
  }
}

CategoryOverpass.prototype._getMarker = function (origGetMarker, origList, ob) {
  if (ob.data[origList.options.prefix + 'MarkerSymbol'].trim() === 'line') {
    let div = document.createElement('div')
    div.className = 'marker'
    div.innerHTML = markers.line(ob.data)

    return div
  } else if (ob.data[origList.options.prefix + 'MarkerSymbol'].trim() === 'polygon') {
    let div = document.createElement('div')
    div.className = 'marker'
    div.innerHTML = markers.polygon(ob.data)

    return div
  }

  return origGetMarker.call(origList, ob)
}

CategoryOverpass.prototype.open = function () {
  if (this.isOpen) {
    return
  }

  CategoryBase.prototype.open.call(this)

  this.layer.addTo(this.map)

  if (!this.lists) {
    this.lists = []

    if (this.data.lists) {
      this.listsDom = []

      let wrapper = document.createElement('div')
      wrapper.className = 'categoryWrapper'
      this.domContent.appendChild(wrapper)

      for (let k in this.data.lists) {
        let listData = this.data.lists[k]
        let list = new OverpassLayerList(this.layer, listData)
        this.lists.push(list)

        let dom = document.createElement('div')
        dom.className = 'category category-list'
        this.listsDom.push(dom)
        wrapper.appendChild(dom)

        let domHeader = document.createElement('header')
        dom.appendChild(domHeader)

        let p = document.createElement('div')
        p.className = 'loadingIndicator'
        p.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">' + lang('loading') + '</span>'
        dom.appendChild(p)

        let name
        if (typeof listData.name === 'undefined') {
          name = k
        } else if (typeof listData.name === 'object') {
          name = lang(listData.name)
        } else {
          name = listData.name
        }

        let a = document.createElement('a')
        a.appendChild(document.createTextNode(name))
        a.href = '#'
        domHeader.appendChild(a)

        a.onclick = () => {
          dom.classList.toggle('open')
          return false
        }

        let domContent = document.createElement('div')
        domContent.className = 'content'
        dom.appendChild(domContent)

        list.addTo(domContent)

        p = document.createElement('div')
        p.className = 'loadingIndicator2'
        p.innerHTML = '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>'
        dom.appendChild(p)
      }
    } else {
      let list = new OverpassLayerList(this.layer, {})
      this.lists.push(list)
      list.addTo(this.domContent)
    }

    this.lists.forEach(list => {
      let origGetMarker = list._getMarker
      list._getMarker = this._getMarker.bind(this, origGetMarker, list)
    })
  }

  this.isOpen = true

  state.update()

  if ('info' in this.data) {
    if (!this.tabInfo) {
      this.tabInfo = new tabs.Tab({
        id: 'info'
      })
      this.tools.add(this.tabInfo)

      this.tabInfo.header.innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i>'
      this.tabInfo.header.title = lang('category-info-tooltip')
      this.domInfo = this.tabInfo.content
      this.domInfo.classList.add('info')

      this.templateInfo = OverpassLayer.twig.twig({ data: this.data.info, autoescape: true })
    }

    this.updateInfo()
  }
}

CategoryOverpass.prototype.updateInfo = function () {
  if (!this.tabInfo) {
    return
  }

  global.currentCategory = this
  var data = {
    layer_id: this.id,
    'const': this.data.const
  }
  if (this.map) {
    data.map = { zoom: map.getZoom() }
  }
  this.domInfo.innerHTML = this.templateInfo.render(data)
  this.updateAssets(this.domInfo)
  global.currentCategory = null
}

CategoryOverpass.prototype.recalc = function () {
  this.layer.recalc()
}

CategoryOverpass.prototype.close = function () {
  if (!this.isOpen) {
    return
  }

  CategoryBase.prototype.close.call(this)

  this.layer.remove()
  this.lists.forEach(list => list.remove())

  state.update()
}

CategoryOverpass.prototype.get = function (id, callback) {
  this.layer.get(id, callback)
}

CategoryOverpass.prototype.show = function (id, options, callback) {
  if (this.currentDetails) {
    this.currentDetails.hide()
  }

  let layerOptions = {
    styles: [ 'selected' ],
    flags: [ 'selected' ]
  }

  let idParts = id.split(/:/)
  switch (idParts.length) {
    case 2:
      id = idParts[1]
      layerOptions.sublayer_id = idParts[0]
      break
    case 1:
      break
    default:
      return callback(new Error('too many id parts! ' + id))
  }

  this.currentDetails = this.layer.show(id, layerOptions,
    function (err, ob, data) {
      if (!err) {
        if (options.showDetails && !options.hasLocation) {
          var preferredZoom = data.data.preferredZoom || 16
          var maxZoom = this.map.getZoom()
          maxZoom = maxZoom > preferredZoom ? maxZoom : preferredZoom
          this.map.flyToBounds(data.object.bounds.toLeaflet(), {
            maxZoom: maxZoom
          })
        }
      }

      callback(err, data)
    }.bind(this)
  )
}

CategoryOverpass.prototype.notifyPopupOpen = function (object, popup) {
  if (this.currentSelected) {
    this.currentSelected.hide()
  }

  let layerOptions = {
    styles: [ 'selected' ],
    flags: [ 'selected' ],
    sublayer_id: object.sublayer_id
  }

  this.updatePopupContent(object, popup)
  this.currentSelected = this.layer.show(object.id, layerOptions, function () {})
}

CategoryOverpass.prototype.notifyPopupClose = function (object, popup) {
  if (this.currentSelected) {
    this.currentSelected.hide()
    this.currentSelected = null
  }

  if (this.currentDetails) {
    this.currentDetails.hide()
    this.currentDetails = null
  }
}

CategoryOverpass.prototype.updatePopupContent = function (object, popup) {
  if (this.popupBodyTemplate) {
    var popupBody = popup._contentNode.getElementsByClassName('popupBody')
    if (!popupBody.length) {
      popupBody = document.createElement('div')
      popupBody.className = 'popupBody'
      popup._contentNode.appendChild(popupBody)
    }

    let html = this.popupBodyTemplate.render(object.twigData)
    if (popupBody.currentHTML !== html) {
      popupBody.innerHTML = html
    }

    popupBody.currentHTML = html
  }

  let id_with_sublayer = (object.sublayer_id === 'main' ? '' : object.sublayer_id + ':') + object.id

  var footer = popup._contentNode.getElementsByClassName('popup-footer')
  if (!footer.length) {
    footer = document.createElement('ul')
    popup._contentNode.appendChild(footer)
    footer.className = 'popup-footer'

    call_hooks_callback('show-popup', object, this, popup._contentNode,
      function (err) {
        if (err.length) {
          console.log('show-popup produced errors:', err)
        }
      }
    )
  }

  var footerContent = '<li><a class="showDetails" href="#' + this.id + '/' + id_with_sublayer + '/details">' + lang('show details') + '</a></li>'
  footerContent += '<li><a target="_blank" class="editLink" href="https://www.openstreetmap.org/edit?editor=id&' + object.object.type + '=' + object.object.osm_id + '">' + lang('edit') + '</a></li>'
  footer.innerHTML = footerContent
}

CategoryOverpass.prototype.renderTemplate = function (object, templateId, callback) {
  OpenStreetBrowserLoader.getTemplate(templateId, this.options, function (err, template) {
    if (err) {
      err = "can't load " + templateId + ': ' + err
      return callback(err, null)
    }

    var result = template.render(object.twigData)

    callback(null, result)
  })
}

OpenStreetBrowserLoader.registerType('overpass', CategoryOverpass)
module.exports = CategoryOverpass
