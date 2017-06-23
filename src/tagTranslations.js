var OverpassLayer = require('overpass-layer')
var translations = null

OverpassLayer.twig.extendFunction('tagTrans', function (key, value, count) {
  return tagTranslationsTrans(key, value, count)
})

function tagTranslationsLoad (path, lang, callback) {
  var req = new XMLHttpRequest()

  req.addEventListener('load', function () {
    if (req.status === 200) {
      translations = JSON.parse(req.responseText)
      callback(null)
    } else {
      callback(req.statusText)
    }
  })

  req.addEventListener('error', function () {
    console.log(req)
    callback('error')
  })

  req.open('GET', path + '/tags/' + lang + '.json')

  req.send()
}

function tagTranslationsTrans (tag, value, count) {
  var ret = null
  var fallback = null

  if (typeof value === 'undefined') {
    fallback = tag

    if (translations && 'tag:' + tag in translations) {
      ret = translations['tag:' + tag]
    }
  } else {
    fallback = value

    if (translations && 'tag:' + tag + '=' + value in translations) {
      ret = translations['tag:' + tag + '=' + value]
    }
  }

  if (ret && typeof ret === 'object') {
    if (typeof count !== 'undefined') {
      if (count > 1 && '!=1' in ret) {
        return ret['!=1']
      } else if ('message' in ret) {
        return ret['message']
      } else {
        return fallback
      }
    } else {
      if ('message' in ret) {
        return ret['message']
      } else {
        return fallback
      }
    }
  } else {
    if (ret !== null) {
      return ret
    } else {
      return fallback
    }
  }
}

module.exports = {
  load: tagTranslationsLoad,
  trans: tagTranslationsTrans
}
