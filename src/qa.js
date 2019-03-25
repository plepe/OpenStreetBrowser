var OverpassLayer = require('overpass-layer')

OverpassLayer.twig.extendFunction('qaDeprecated', function (wrong, instead) {
  if (!global.twigContext.data.qa) {
    global.twigContext.data.qa = []
  }

  global.twigContext.data.qa.push([
    'deprecated',
    wrong,
    instead
  ])
})
OverpassLayer.twig.extendFunction('qaWrong', function (wrong, instead) {
  if (!global.twigContext.data.qa) {
    global.twigContext.data.qa = []
  }

  global.twigContext.data.qa.push([
    'wrong',
    wrong,
    instead
  ])
})
OverpassLayer.twig.extendFunction('qaMissing', function (tag) {
  if (!global.twigContext.data.qa) {
    global.twigContext.data.qa = []
  }

  global.twigContext.data.qa.push([
    'missing',
    tag
  ])
})
OverpassLayer.twig.extendFunction('qaSuggested', function (tag) {
  if (!global.twigContext.data.qa) {
    global.twigContext.data.qa = []
  }

  global.twigContext.data.qa.push([
    'suggested',
    tag
  ])
})

function updateObject (param) {
  let feature = param.sublayerFeature

  console.log(param.dom, feature)
}

module.exports = {
  updateObject
}
