var OverpassLayer = require('overpass-layer')
var OpeningHours = require('opening_hours')
var colorInterpolate = require('color-interpolate')
var osmParseDate = require('openstreetmap-date-parser')
const natsort = require('natsort')

OverpassLayer.twig.extendFunction('tagsPrefix', function (tags, prefix) {
  var ret = {}
  var count = 0

  for (var k in tags) {
    if (k.substr(0, prefix.length) === prefix) {
      ret[k.substr(prefix.length)] = k
      count++
    }
  }

  if (count === 0) {
    return null
  }

  return ret
})

OverpassLayer.twig.extendFunction('openingHoursState', function (openingHours) {
  try {
    var oh = new OpeningHours(openingHours)
  } catch (err) {
    console.log('Error in opening_hours: ' + err)
    return 'unknown'
  }

  return oh.getStateString(new Date(), true)
})
OverpassLayer.twig.extendFilter('websiteUrl', function (value) {
  if (value.match(/^https?:\/\//)) {
    return value
  }

  return 'http://' + value
})
OverpassLayer.twig.extendFilter('matches', function (value, match) {
  return value.toString().match(match)
})
OverpassLayer.twig.extendFilter('natsort', function (values, options) {
  return values.sort(natsort(options))
})
OverpassLayer.twig.extendFilter('unique', function (values, options) {
  // source: https://stackoverflow.com/a/14438954
  function onlyUnique(value, index, self) {
    return self.indexOf(value) === index
  }
  return values.filter(onlyUnique)
})
OverpassLayer.twig.extendFunction('colorInterpolate', function (map, value) {
  var colormap = colorInterpolate(map)
  return colormap(value)
})
OverpassLayer.twig.extendFilter('osmParseDate', function (value) {
  return osmParseDate(value)
})
OverpassLayer.twig.extendFunction('evaluate', function (tags) {
  var ob = {
    id: 'x0',
    isShown: true,
    layer_id: global.currentCategory.id,
    object: {
      id: 'x0',
      meta: {},
      tags: tags,
      type: 'special'
    }
  }

  var d = global.currentCategory.layer.mainlayer.evaluate(ob)
  return d
})
