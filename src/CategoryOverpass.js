var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var CategoryBase = require('./CategoryBase')
var defaultValues = {
  minZoom: 14,
  feature: {
    title: "{{ localizedTag(tags, 'name') |default(localizedTag(tags, 'operator')) | default(localizedTag(tags, 'ref')) | default(trans('unnamed')) }}",
    markerSign: ""
  },
  queryOptions: {
    split: 64
  }
}

CategoryOverpass.prototype = Object.create(CategoryBase.prototype)
CategoryOverpass.prototype.constructor = CategoryOverpass
function CategoryOverpass (id, data) {
  CategoryBase.call(this, id, data)

  data.id = this.id

  // set undefined data properties from defaultValues
  for (var k1 in defaultValues) {
    if (!(k1 in data)) {
      data[k1] = defaultValues[k1]
    } else if (typeof defaultValues[k1] === 'object') {
      for (var k2 in defaultValues[k1]) {
        if (!(k2 in data[k1])) {
          data[k1][k2] = defaultValues[k1][k2]
        } else if (typeof defaultValues[k1][k2] === 'object') {
          for (var k3 in defaultValues[k1][k2]) {
            if (!(k3 in data[k1][k2])) {
              data[k1][k2][k3] = defaultValues[k1][k2][k3]
            }
          }
        }
      }
    }
  }

  data.feature.appUrl = '#' + this.id + '/{{ id }}'
  data.feature.body = (typeof data.feature.body === 'string' ? data.feature.body : '') + '<a class="showDetails" href="#' + this.id + '/{{ id }}/details">show details</a>'

  this.layer = new OverpassLayer(data)

  this.layer.onLoadStart = function (ev) {
    this.dom.classList.add('loading')
  }.bind(this)
  this.layer.onLoadEnd = function (ev) {
    this.dom.classList.remove('loading')

    if (ev.error && ev.error !== 'abort') {
      alert('Error loading data from Overpass API: ' + ev.error)
    }
  }.bind(this)

  var p = document.createElement('div')
  p.className = 'loadingIndicator'
  p.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  this.dom.appendChild(p)

  var p = document.createElement('div')
  p.className = 'loadingIndicator2'
  p.innerHTML = '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>'
  this.dom.appendChild(p)

  this.domStatus = document.createElement('div')
  this.domStatus.className = 'status'

  this.dom.appendChild(this.domStatus)
}

CategoryOverpass.prototype.setMap = function (map) {
  CategoryBase.prototype.setMap.call(this, map)

  this.map.on('zoomend', this.updateStatus.bind(this))
  this.updateStatus()
}

CategoryOverpass.prototype.updateStatus = function () {
  this.domStatus.innerHTML = ''

  if (typeof this.data.query === 'object') {
    var highestZoom = Object.keys(this.data.query).reverse()[0]
    if (this.map.getZoom() < highestZoom) {
      this.domStatus.innerHTML = 'zoom in for more map features'
    }
  }

  if ('minZoom' in this.data && this.map.getZoom() < this.data.minZoom) {
    this.domStatus.innerHTML = 'zoom in for map features to appear'
  }
}

CategoryOverpass.prototype.open = function () {
  if (this.isOpen)
    return

  CategoryBase.prototype.open.call(this)

  this.layer.addTo(this.map)

  if (!this.list) {
    this.list = new OverpassLayerList(this.domContent, this.layer)
  }

  this.isOpen = true
}

CategoryOverpass.prototype.recalc = function () {
  this.layer.recalc()
}

CategoryOverpass.prototype.close = function () {
  if (!this.isOpen)
    return

  CategoryBase.prototype.close.call(this)

  this.layer.remove()
  this.list.remove()
}

CategoryOverpass.prototype.get = function (id, callback) {
  this.layer.get(id, callback)
}

CategoryOverpass.prototype.show = function (id, options, callback) {
  this.layer.show(id, options, callback)
}

OpenStreetBrowserLoader.registerType('overpass', CategoryOverpass)
module.exports = CategoryOverpass
