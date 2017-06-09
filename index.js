var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')

if (typeof window !== 'undefined') {
  window.OverpassLayer = OverpassLayer
  window.OverpassLayerList = OverpassLayerList
  window.OverpassFrontend = OverpassFrontend
}
