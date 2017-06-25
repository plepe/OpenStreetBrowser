var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List

function OpenStreetBrowserCategory (id, data) {
  this.id = id
  data.id = id
  this.layer = new OverpassLayer(data)
  this.isOpen = false
  this.dom = document.createElement('div')
}

OpenStreetBrowserCategory.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserCategory.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
  if (typeof this.parentDom !== 'string') {
    this.parentDom.appendChild(this.dom)

    if (this.isOpen) {
      this.parentDom.parentNode.classList.add('open')
    }
  }
}

OpenStreetBrowserCategory.prototype.open = function () {
  if (this.isOpen)
    return

  if (typeof this.parentDom === 'string') {
    var d = document.getElementById(this.parentDom)
    if (d) {
      this.parentDom = d
      this.parentDom.appendChild(this.dom)
    }
  }

  if (this.parentDom && this.parentDom.parentNode) {
    this.parentDom.parentNode.classList.add('open')
  }

  this.layer.addTo(this.map)

  if (!this.list) {
    this.list = new OverpassLayerList(this.dom, this.layer)
  }

  this.isOpen = true
}

OpenStreetBrowserCategory.prototype.close = function () {
  if (!this.isOpen)
    return

  this.layer.remove()
  this.list.remove()

  if (this.parentDom && this.parentDom.parentNode) {
    this.parentDom.parentNode.classList.remove('open')
  }

  this.isOpen = false
}

OpenStreetBrowserCategory.prototype.get = function (id, callback) {
  this.layer.get(id, callback)
}

OpenStreetBrowserCategory.prototype.show = function (id, options, callback) {
  this.layer.show(id, options, callback)
}

OpenStreetBrowserCategory.prototype.toggle = function () {
  if (this.isOpen) {
    this.close()
  } else {
    this.open()
  }
}

OpenStreetBrowserLoader.registerType('category', OpenStreetBrowserCategory)
module.exports = OpenStreetBrowserCategory
