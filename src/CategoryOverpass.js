/* global openstreetbrowserPrefix */
/* eslint camelcase: 0 */
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var LeafletGeowiki = require('leaflet-geowiki/minimal')
const isTrue = require('overpass-layer/src/isTrue')
var OverpassLayerList = require('overpass-layer').List
var queryString = require('query-string')

var CategoryBase = require('./CategoryBase')
var tabs = require('modulekit-tabs')
var maki = require('./maki')
var qs = require('sheet-router/qs')
const ObjectDisplay = require('./ObjectDisplay')

const showMore = require('./showMore')

const listTemplate = '<a href="{{ object.appUrl|default("#") }}">' +
  '<div class="marker">' +
  '{% if object.templateMarkerSymbol|default(object.markerSymbol)|trim == "line" %}' +
  '<div class="symbol">{{ markerLine(object, {ignoreStyles:["hover"]}) }}</div>' +
  '{% elseif object.templateMarkerSymbol|default(object.markerSymbol)|trim == "polygon" %}' +
  '<div class="symbol">{{ markerPolygon(object, {ignoreStyles:["hover"]}) }}</div>' +
  '{% elseif object.templateMarkerSymbol or object.markerSymbol %}' +
  '<div class="symbol">{{ object.templateMarkerSymbol|default(object.markerSymbol) }}</div>' +
  '{% elseif object.marker and object.marker.iconUrl %}' +
  '<img class="symbol" src="{{ object.marker.iconUrl|e }}">' +
  '{% endif %}' +
  '{% if object.templateMarkerSign or object.markerSign %}' +
  '<div class="sign">{{ object.templateMarkerSign|default(object.markerSign) }}</div>' +
  '{% endif %}' +
  '</div>' +
  '<div class="content">' +
  '{% if object.templateDetails or object.details %}<div class="details">{{ object.templateDetails|default(object.details) }}</div>{% endif %}' +
  '{% if object.templateDescription or object.description %}<div class="description">{{ object.templateDescription|default(object.description) }}</div>{% endif %}' +
  '{% if object.templateTitle or object.title %}<div class="title">{{ object.templateTitle|default(object.title) }}</div>{% endif %}' +
  '</div>' +
  '</a>'

var defaultValues = {
  feature: {
    title: "{{ localizedTag(tags, 'name') |default(localizedTag(tags, 'operator')) | default(localizedTag(tags, 'ref')) }}",
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
  layouts: {
    list: listTemplate.replace(/template/g, 'list'),
    popup:
      '<h1>{{ object.popupTitle|default(object.title) }}</h1>' +
      '{% if object.popupDescription or object.description %}<div class="description">{{ object.popupDescription|default(object.description) }}</div>{% endif %}' +
      '{% if object.popupBody or object.body %}<div class="body">{{ object.popupBody|default(object.body) }}</div>{% endif %}'
  },
  queryOptions: {
  }
}

CategoryOverpass.prototype = Object.create(CategoryBase.prototype)
CategoryOverpass.prototype.constructor = CategoryOverpass
function CategoryOverpass (options, data, repository) {
  var p

  CategoryBase.call(this, options, data, repository)

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
  data.layouts.popup = () => null

  this.layer = new LeafletGeowiki({
    style: data,
    overpassFrontend: global.overpassFrontend
  })

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

    this.emit('update', object, ob)
  }.bind(this))
  this.layer.on('add', (ob, data) => this.emit('add', ob, data))
  this.layer.on('remove', (ob, data) => this.emit('remove', ob, data))
  this.layer.on('zoomChange', (ob, data) => this.emit('remove', ob, data))
  this.layer.on('twigData',
    (ob, data, result) => {
      result.user = global.options
      global.currentCategory = this
    }
  )


  call_hooks('category-overpass-init', this)

  var p = document.createElement('div')
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

      let id = this.id

      let param = {}
      this.emit('stateGet', param)

      for (var k in param) {
        if (!param[k]) {
          delete param[k]
        }
      }

      if (param && Object.keys(param).length) {
        id += '[' + queryString.stringify(param) + ']'
      }

      state.categories += id
    }
  }.bind(this))

  register_hook('state-apply', function (state) {
    if (!('categories' in state)) {
      return
    }

    let list = state.categories.split(',')
    let found = list.filter(id => {
      let m = id.match(/^([0-9A-Z_-]+)(\[(.*)\])/i)
      if (m) {
        id = m[1]
      }

      return id === this.id
    }).length

    if (!found) {
      this.close()
    }

    // opening categories is handled by src/categories.js
  }.bind(this))
}

CategoryOverpass.prototype.setParam = function (param) {
  this.emit('setParam', param)
  this._applyParam(param)
}

CategoryOverpass.prototype._applyParam = function (param) {
  this.emit('applyParam', param)
}

CategoryOverpass.prototype.updateAssets = function (div) {
  var imgs = div.getElementsByTagName('img')
  for (var i = 0; i < imgs.length; i++) {
    let img = imgs[i]

    // TODO: 'src' is deprecated, use only data-src
    var src = img.getAttribute('src') || img.getAttribute('data-src')
    if (src === null) {
    } else if (src.match(/^(maki|temaki):.*/)) {

      /* HACK for temaki icons: as some icons are larger than the default 15px, force  size to 15px. */
      if (src.match(/^temaki:/) && !img.hasAttribute('width') && !img.hasAttribute('height')) {
        img.setAttribute('width', '15')
        img.setAttribute('height', '15')
      }

      let m = src.match(/^(maki|temaki):([a-z0-9-_]*)(?:\?(.*))?$/)
      if (m) {
        maki(m[1], m[2], m[3] ? qs(m[3]) : {}, function (err, result) {
          if (err === null) {
            img.setAttribute('src', result)
          }
        })
      }
    } else if (src.match(/^(marker):.*/)) {
      let m = src.match(/^(marker):([a-z0-9-_]*)(?:\?(.*))?$/)
      if (m) {
        let span = document.createElement('span')
        img.parentNode.insertBefore(span, img)
        img.parentNode.removeChild(img)
        i--
        let param = m[3] ? qs(m[3]) : {}

        if (param.styles) {
          let newParam = { styles: param.styles }
          for (let k in param) {
            let m = k.match(/^(style|style:.*)?:([^:]*)$/)
            if (m) {
              if (!(m[1] in newParam)) {
                newParam[m[1]] = {}
              }
              newParam[m[1]][m[2]] = param[k]
            }
          }
          param = newParam
        }

        span.innerHTML = markers[m[2]](param)
      }
    } else if (!src.match(/^(https?:|data:|\.|\/)/)) {
      img.setAttribute('src', (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
      'asset.php?repo=' + this.options.repositoryId + '&file=' + encodeURIComponent(img.getAttribute('data-src') || img.getAttribute('src')))
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

CategoryOverpass.prototype.setParentDom = function (parentDom) {
  CategoryBase.prototype.setParentDom.call(this, parentDom)
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

CategoryOverpass.prototype.open = function () {
  if (this.isOpen) {
    return
  }

  CategoryBase.prototype.open.call(this)

  this.layer.addTo(this.map)

  if (!this.lists) {
    this.lists = []
    this.listsDom = []

    if (this.data.lists) {
      let wrapper = document.createElement('div')
      wrapper.className = 'categoryWrapper'
      this.domContent.appendChild(wrapper)

      for (let k in this.data.lists) {
        let listData = this.data.lists[k]

        this.layer.setLayout(listData.prefix, listTemplate.replace(/template/g, listData.prefix))

        let list = new OverpassLayerList(this.layer.layers, listData)
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

        showMore(this, domContent)

        p = document.createElement('div')
        p.className = 'loadingIndicator2'
        p.innerHTML = '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>'
        dom.appendChild(p)
      }
    } else {
      let list = new OverpassLayerList(this.layer.layers, {})
      this.lists.push(list)
      list.addTo(this.domContent)
      this.listsDom.push(this.domContent)

      showMore(this, this.domContent)
    }
  }

  this.listsDom.forEach(dom => dom.classList.add('open'))

  this.isOpen = true

  global.state.update() // TODO

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
    }

    if (!this.templateInfo) {
      this.templateInfo = OverpassLayer.twig.twig({ data: this.data.info, autoescape: true, rethrow: true })
    }

    this.updateInfo()
  }

  this.emit('open')
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
  this.emit('updateInfo', data)
  if (this.map) {
    data.map = {
      zoom: this.map.getZoom(),
      metersPerPixel: this.map.getMetersPerPixel()
    }
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

  global.state.update() // TODO
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
        if (!options.hasLocation) {
          var preferredZoom = data.data.preferredZoom || 16
          var maxZoom = this.map.getZoom()
          maxZoom = maxZoom > preferredZoom ? maxZoom : preferredZoom
          this.map.flyToBounds(data.object.bounds.toLeaflet({ shiftWorld: this.layer.getShiftWorld() }), {
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

  if (popup._contentNode) {
    popup._contentNode.style = ''
  }

  this.currentSelected = this.layer.show(object.id, layerOptions, function () {})

  this.currentPopupDisplay = new ObjectDisplay({
    feature: object,
    category: this,
    dom: popup._contentNode,
    displayId: 'popup'
  }, () => {})

  // Move close button into the content, to make its position depending whether a scrollbar is visible or not
  popup._closeButton.setAttribute('data-order', -1001)
  popup._contentNode.insertBefore(popup._closeButton, popup._contentNode.firstChild)
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

  if (this.currentPopupDisplay) {
    this.currentPopupDisplay.close()
    delete this.currentPopupDisplay
  }
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

CategoryOverpass.prototype.allMapFeatures = function (callback) {
  if (!this.isOpen) {
    return callback(null, [])
  }

  let list = Object.values(this.layer.mainlayer.visibleFeatures)

  list = list.filter(item => !isTrue(item.data.exclude))

  callback(null, list)
}

CategoryOverpass.defaultValues = defaultValues

OpenStreetBrowserLoader.registerType('overpass', CategoryOverpass)
module.exports = CategoryOverpass
