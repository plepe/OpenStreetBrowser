var OverpassLayer = require('overpass-layer')

function cssStyle (style) {
  var ret = ''
  if ('color' in style) {
    ret += 'stroke: ' + style.color + ';'
  }
  if ('weight' in style) {
    ret += 'stroke-width: ' + style.weight + ';'
  }
  if ('dashArray' in style) {
    ret += 'stroke-dasharray: ' + style.dashArray + ';'
  }
  if ('dashArray' in style) {
    ret += 'stroke-dasharray: ' + style.dashArray + ';'
  }
  if ('dashOffset' in style) {
    ret += 'stroke-dashoffset: ' + style.dashOffset + ';'
  }
  if ('fillColor' in style) {
    ret += 'fill: ' + style.fillColor + ';'
  }

  return ret
}

function markerLine (data) {
  var ret = '<svg anchorX="13" anchorY="8" width="25" height="15">'

  if (!('styles' in data)) {
    data = {
      style: data,
      styles: [ 'default' ]
    }
  }

  for (var i = 0; i < data.styles.length; i++) {
    var k = data.styles[i]
    var style = k === 'default' ? data.style : data['style:' + k]

    ret += '<line x1="0" y1="8" x2="25" y2="8" style="' + cssStyle(style) + '"/>'
  }

  ret += '</svg>'

  return ret
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
