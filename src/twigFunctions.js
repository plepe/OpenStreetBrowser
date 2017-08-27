var OverpassLayer = require('overpass-layer')
var OpeningHours = require('opening_hours')

OverpassLayer.twig.extendFunction('tagsPrefix', function (tags, prefix) {
  var ret = {}
  var count = 0

  for (var k in tags) {
    if (k.substr(0, prefix.length) === prefix) {
      ret[k.substr(prefix.length)] = k
      count++
    }
  }

  if (count == 0) {
    return null
  }

  return ret
})

OverpassLayer.twig.extendFunction('openingHoursState', function (opening_hours) {
  try {
    var oh = new OpeningHours(opening_hours)
  } catch (err) {
    console.log("Error in opening_hours: " + err)
    return 'unknown'
  }

  return oh.getStateString(new Date(), true)
})
