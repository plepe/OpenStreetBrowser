var OverpassLayer = require('overpass-layer')

function cssStyle (style) {
  var ret = ''
  if ('color' in style) {
    ret += 'stroke: ' + style.color + ';'
  }
  ret += 'stroke-width: ' + ('width' in style ? style.width : '3') + ';'
  if ('dashArray' in style) {
    ret += 'stroke-dasharray: ' + style.dashArray + ';'
  }
  if ('dashArray' in style) {
    ret += 'stroke-dasharray: ' + style.dashArray + ';'
  }
  if ('dashOffset' in style) {
    ret += 'stroke-dashoffset: ' + style.dashOffset + ';'
  }
  if ('offset' in style) {
    ret += 'stroke-offset: ' + style.dashOffset + ';'
  }
  if ('fillColor' in style) {
    ret += 'fill: ' + style.fillColor + ';'
  } else if ('color' in style) {
    ret += 'fill: ' + style.color + ';'
  } else {
    ret += 'fill: #3388ff;'
  }
  if ('fillOpacity' in style) {
    ret += 'fill-opacity: ' + style.fillOpacity + ';'
  } else {
    ret += 'fill-opacity: 0.2;'
  }

  return ret
}

function markerLine (data) {
  var ret = '<svg anchorX="13" anchorY="8" width="25" height="15">'

  data = parseOptions(data)

  for (var i = 0; i < data.styles.length; i++) {
    var k = data.styles[i]
    var style = (k === 'default' ? data.style : data['style:' + k]) || {}
    var y = 8.0 + parseFloat('offset' in style ? style.offset : 0)

    ret += '<line x1="0" y1="' + y + '" x2="25" y2="' + y + '" style="' + cssStyle(style) + '"/>'
  }

  ret += '</svg>'

  return ret
}

function markerPolygon (data) {
  var ret = '<svg anchorX="13" anchorY="8" width="25" height="25">'

  data = parseOptions(data)

  for (var i = 0; i < data.styles.length; i++) {
    var k = data.styles[i]
    var style = (k === 'default' ? data.style : data['style:' + k]) || {}

    ret += '<rect x="3" y="3" width="18" height="18" style="' + cssStyle(style) + '"/>'
  }

  ret += '</svg>'

  return ret
}

function markerCircle (data) {
  var ret = '<svg anchorX="13" anchorY="13" width="25" height="25">'

  data = parseOptions(data)

  for (var i = 0; i < data.styles.length; i++) {
    var k = data.styles[i]
    var style = (k === 'default' ? data.style : data['style:' + k]) || {}

    ret += '<circle cx="12.5" cy="12.5" r="' + (style.radius || 12) + '" style="' + cssStyle(style) + '"/>'
  }

  ret += '</svg>'
  console.log(ret)

  return ret
}

function markerPointer (data) {
  var ret = '<svg anchorX="13" anchorY="45" width="25" height="45" signAnchorX="0" signAnchorY="-31">'

  data = parseOptions(data)

  for (var i = 0; i < data.styles.length; i++) {
    var k = data.styles[i]
    var style = (k === 'default' ? data.style : data['style:' + k]) || {}

    ret += '<path d="M0.5,12.5 A 12,12 0 0 1 24.5,12.5 C 24.5,23 13,30 12.5,44.5 C 12,30 0.5,23 0.5,12.5" style="' + cssStyle(style) + '"/>'
  }

  ret += '</svg>'

  return ret
}

function parseOptions (data) {
  if (!data || !('style' in data) && !('styles' in data)) {
    let ret = {
      style: { fillColor: '#f2756a', color: '#000000', width: 1, radius: 12, fillOpacity: 1 },
      styles: [ 'default' ]
    }

    if (data && data.color) {
      ret.style.fillColor = data.color
      ret.style.fillOpacity = 0.2
    }

    if (data) for (let k in data) {
      ret.style[k] = data[k]
    }

    return ret
  }

  if (!('styles' in data)) {
    data = {
      style: data,
      styles: [ 'default' ]
    }
  }

  if (typeof data.styles === 'string') {
    data.styles = data.styles.split(/,/g)
  }

  return data
}

OverpassLayer.twig.extendFunction('markerLine', markerLine)
OverpassLayer.twig.extendFunction('markerCircle', markerCircle)
OverpassLayer.twig.extendFunction('markerPointer', markerPointer)
OverpassLayer.twig.extendFunction('markerPolygon', markerPolygon)

module.exports = {
  line: markerLine,
  circle: markerCircle,
  pointer: markerPointer,
  polygon: markerPolygon
}
