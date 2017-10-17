var OverpassLayer = require('overpass-layer')

function markerLine (style) {
  var color = 'color' in style ? style.color : '#000000'
  var weight = 'weight' in style ? style.weight : 1

  return '<svg anchorX="13" anchorY="8" width="25" height="15"><line x1="0" y1="8" x2="25" y2="8" style="stroke: ' + color + '; stroke-width: ' + weight + '"/></svg>'
}

function markerCircle (style) {
  var fillColor = 'fillColor' in style ? style.fillColor : '#f2756a'
  var color = 'color' in style ? style.color : '#000000'
  var weight = 'weight' in style ? style.weight : 1

  return '<svg anchorX="13" anchorY="13" width="25" height="25"><circle cx="12.5" cy="12.5" r="12" style="stroke: ' + color + '; stroke-width: ' + weight + '; fill: ' + fillColor + ';"/></svg>'
}

function markerPointer (style) {
  var fillColor = 'fillColor' in style ? style.fillColor : '#f2756a'
  var color = 'color' in style ? style.color : '#000000'
  var weight = 'weight' in style ? style.weight : 1

  return '<svg anchorX="13" anchorY="45" width="25" height="45"><path d="M0.5,12.5 A 12,12 0 0 1 24.5,12.5 C 24.5,23 13,30 12.5,44.5 C 12,30 0.5,23 0.5,12.5" style="stroke: ' + color + '; stroke-width: ' + weight + '; fill: ' + fillColor + ';"/></svg>'
}

OverpassLayer.twig.extendFunction('markerLine', markerLine)
OverpassLayer.twig.extendFunction('markerCircle', markerCircle)
OverpassLayer.twig.extendFunction('markerPointer', markerPointer)

module.exports = {
  line: markerLine,
  circle: markerCircle,
  pointer: markerPointer
}
