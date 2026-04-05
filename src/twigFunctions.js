var OverpassLayer = require('@geowiki-net/leaflet-geowiki-layer')
const Events = require('events')

const cardinalDirections = {
  'NORTH': 0,
  'N': 0,
  'NNE': 22.5,
  'NE': 45,
  'NORTHEAST': 45,
  'ENE': 67.5,
  'EAST': 90,
  'E': 90,
  'ESE': 112.5,
  'SE': 135,
  'SOUTHEAST': 45,
  'SSE': 157.5,
  'SOUTH': 180,
  'S': 180,
  'SSW': 202.5,
  'SW': 225,
  'SOUTHWEST': 225,
  'WSW': 247.5,
  'WEST': 270,
  'W': 270,
  'WNW': 292.5,
  'NW': 315,
  'NORTHWEST': 315,
  'NNW': 337.5
}

OverpassLayer.twig.extendFilter('parseDirection', function (value, options) {
  if (typeof value === 'string') {
    const valueUpper = value.trim().toUpperCase()
    if (valueUpper in cardinalDirections) {
      return cardinalDirections[valueUpper]
    }

    return parseFloat(value)
  }

  return value
})
OverpassLayer.twig.extendFunction('evaluate', function (tags) {
  const ob = new Events({
    id: 'x0',
    meta: {},
    tags: tags,
    type: 'special'
  })

  return global.currentCategory.layer.mainlayer.evaluate(ob)
})
function enumerate (list) {
  if (!list) {
    return ''
  }

  if (typeof list === 'string') {
    list = list.split(/;/g)
  }

  if (list.length > 2) {
    let result = lang_str.enumerate_start.replace('{0}', list[0]).replace('{1}', list[1])

    for (let i = 2; i < list.length - 1; i++) {
      result = lang_str.enumerate_middle.replace('{0}', result).replace('{1}', list[i])
    }

    return lang_str.enumerate_end.replace('{0}', result).replace('{1}', list[list.length - 1])
  }
  else if (list.length == 2) {
    return lang_str.enumerate_2.replace('{0}', list[0]).replace('{1}', list[1])
  }
  else if (list.length > 0) {
    return list[0]
  }

  return ''
}
OverpassLayer.twig.extendFunction('enumerate', (list) => enumerate(list))
OverpassLayer.twig.extendFilter('enumerate', (list) => enumerate(list))
