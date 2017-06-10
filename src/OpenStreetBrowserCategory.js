var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List

function OpenStreetBrowserCategory (id, data) {
  this.id = id
  this.layer = new OverpassLayer(data.query, data)
}

OpenStreetBrowserCategory.prototype.addTo = function (map, parentDom) {
  this.map = map
  this.parentDom = parentDom

  this.layer.addTo(this.map)

  if (!this.list) {
    this.list = new OverpassLayerList(this.parentDom, this.layer)
  }

  this.isOpen = true
}

OpenStreetBrowserCategory.prototype.remove = function () {
  this.layer.remove()
  this.list.remove()

  this.isOpen = false
}

OpenStreetBrowserCategory.prototype.toggle = function () {
  if (this.isOpen) {
    this.remove()
  } else {
    this.addTo(this.map, this.parentDom)
  }
}

module.exports = OpenStreetBrowserCategory
