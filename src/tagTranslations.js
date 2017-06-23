var translations = null

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

  if (typeof value === 'undefined') {
    if (translations && 'tag:' + tag in translations) {
      ret = translations['tag:' + tag]
    } else {
      ret = tag
    }
  }
  else if (translations && 'tag:' + tag + '=' + value in translations) {
    ret = translations['tag:' + tag + '=' + value]
  } else {
    ret = value
  }

  if (ret && typeof ret === 'object') {
    if (typeof count !== 'undefined') {
      if (count > 1 && '!=1' in ret) {
        return ret['!=1']
      } else {
        return ret['message']
      }
    } else {
      return ret['message']
    }
  } else {
    return ret
  }
}

module.exports = {
  load: tagTranslationsLoad,
  trans: tagTranslationsTrans
}
