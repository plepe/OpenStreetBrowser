const markers = require('openstreetbrowser-markers')
var OverpassLayer = require('overpass-layer')

OverpassLayer.twig.extendFunction('markerLine', (data) => OverpassLayer.twig.filters.raw(markers.line(data)))
OverpassLayer.twig.extendFunction('markerCircle', (data) => OverpassLayer.twig.filters.raw(markers.circle(data)))
OverpassLayer.twig.extendFunction('markerPointer', (data) => OverpassLayer.twig.filters.raw(markers.pointer(data)))
OverpassLayer.twig.extendFunction('markerPolygon', (data) => OverpassLayer.twig.filters.raw(markers.polygon(data)))

module.exports = {
  line: markers.line,
  circle: markers.circle,
  pointer: markers.pointer,
  polygon: markers.polygon
}
