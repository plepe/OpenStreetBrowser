var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List

function OpenStreetBrowserCategory (id, data) {
  this.id = id
  this.layer = new OverpassLayer(data.query, data)
  this.isOpen = false
}

OpenStreetBrowserCategory.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserCategory.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
}

OpenStreetBrowserCategory.prototype.open = function () {
  if (this.isOpen)
    return

  if (typeof this.parentDom === 'string') {
    this.parentDom = document.getElementById(this.parentDom)
  }

  this.layer.addTo(this.map)

  if (!this.list) {
    this.list = new OverpassLayerList(this.parentDom, this.layer)
  }

  this.isOpen = true
}

OpenStreetBrowserCategory.prototype.close = function () {
  if (!this.isOpen)
    return

  this.layer.remove()
  this.list.remove()

  this.isOpen = false
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
