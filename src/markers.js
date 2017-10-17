var OverpassLayer = require('overpass-layer')

function markerLine (style) {
  return '<svg width="25" height="15"><line x1="0" y1="8" x2="25" y2="8" style="stroke: ' + style.color + '; stroke-width: ' + style.weight + '"/></svg>'
}

function markerCircle (style) {
  var fill = 'fillColor' in style ? style.fillColor : '#f2756a'
  var strokeWidth = 'weight' in style ? style.weight : 1
  var strokeColor = 'border' in style ? style.border : '#000000'

  return '<svg width="25" height="25"><circle cx="12.5" cy="12.5" r="12" style="stroke: ' + strokeColor + '; stroke-width: ' + strokeWidth + '; fill: ' + fill + ';"/></svg>'
}

function markerPointer (style) {
  var fill = 'fillColor' in style ? style.fillColor : '#f2756a'
  var strokeWidth = 'weight' in style ? style.weight : 1
  var strokeColor = 'border' in style ? style.border : '#000000'

  return '<svg width="25" height="45"><path d="M0.5,12.5 A 12,12 0 0 1 24.5,12.5 C 24.5,23 13,30 12.5,44.5 C 12,30 0.5,23 0.5,12.5" style="stroke: ' + strokeColor + '; stroke-width: ' + strokeWidth + '; fill: ' + fill + ';"/></svg>'
}

OverpassLayer.twig.extendFunction('markerLine', markerLine)
OverpassLayer.twig.extendFunction('markerCircle', markerCircle)
OverpassLayer.twig.extendFunction('markerPointer', markerPointer)

module.exports = {
  line: markerLine,
  circle: markerCircle,
  pointer: markerPointer
}
