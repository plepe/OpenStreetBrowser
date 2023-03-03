const markers = require('openstreetbrowser-markers')
var OverpassLayer = require('overpass-layer')

OverpassLayer.twig.extendFunction('markerLine', (data, options) => OverpassLayer.twig.filters.raw(markers.line(data, options)))
OverpassLayer.twig.extendFunction('markerCircle', (data, options) => OverpassLayer.twig.filters.raw(markers.circle(data, options)))
OverpassLayer.twig.extendFunction('markerPointer', (data, options) => OverpassLayer.twig.filters.raw(markers.pointer(data, options)))
OverpassLayer.twig.extendFunction('markerPolygon', (data, options) => OverpassLayer.twig.filters.raw(markers.polygon(data, options)))

module.exports = {
  line: markers.line,
  circle: markers.circle,
  pointer: markers.pointer,
  polygon: markers.polygon
}
