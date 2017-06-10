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
  new OverpassLayerList(this.parentDom, this.layer);
}

module.exports = OpenStreetBrowserCategory
