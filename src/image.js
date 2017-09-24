var wikidata = require('./wikidata')
var cache = {}

function showImage (url, dom) {
  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="' + url +'"><img src="' + url + '"></a>'

  dom.appendChild(div)
}

function showWikimediaImage (value, dom) {
  var url = 'https://commons.wikimedia.org/w/thumb.php?f=' + encodeURIComponent(value) + '&w=' + 235 //imgSize

  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(value) + '"><img src="' + url + '"/></a>'

  dom.appendChild(div)
}

// feature: { id: 'File:xxx.jpg', type: 'wikimedia|url', url: 'https://...' }
function imageLoadAll(data, featureCallback, finalCallback) {
  var id
  var foundImages = []
  var ret = []
  var r
  var callbackCount = 1

  if (data.id in cache) {
    cache[data.id].forEach(function (d) {
      featureCallback(null, d)
    })
    return finalCallback(null)
  }

  if (data.object.tags.image) {
    img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      id = img.substr(5)
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(39))
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(40))
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else {
      foundImages.push(img)
      r = {
        id: img,
        type: 'url'
      }
      ret.push(r)
      featureCallback(null, r)
    }
  }

  if (data.object.tags.wikidata) {
    wikidata.load(data.object.tags.wikidata, function (err, result) {
      if (result && result.claims && result.claims.P18) {
        result.claims.P18.forEach(function (d) {
          id = d.mainsnak.datavalue.value

          if (foundImages.indexOf(id) === -1) {
            r = {
              id: id,
              type: 'wikimedia'
            }
            ret.push(r)
            featureCallback(null, r)
            foundImages.push(id)
          }
        })
      }

      checkCallback()
    })

    callbackCount++
  }

  if (data.object.tags.wikimedia_commons) {
    var value = data.object.tags.wikimedia_commons

    if (value.substr(0, 9) === 'Category:') {
      ajax('wikimedia', { page: value }, function (result) {
        if (result.images) {
          result.images.forEach(function (d) {
            if (foundImages.indexOf(d) === -1) {
              foundImages.push(d)
	      r = {
		id: d,
		type: 'wikimedia'
	      }
              ret.push(r)
              featureCallback(null, r)
            }
          })
        }

        checkCallback()
      })

      callbackCount++
    } else if (value.substr(0, 5) === 'File:') {
      var id = value.substr(5)
      if (foundImages.indexOf(id) === -1) {
        foundImages.push(id)
        r = {
          id: id,
          type: 'wikimedia'
        }
        ret.push(r)
        featureCallback(null, r)
      }
    }
  }

  checkCallback()

  function checkCallback () {
    callbackCount--

    if (callbackCount === 0) {
      cache[data.id] = ret
      finalCallback(null)
    }
  }
}

function show(img, options, div) {
  switch (img.type) {
    case 'wikimedia':
      showWikimediaImage(img.id, div)
      break;
    case 'url':
      showImage(img.id, div)
      break;
    default:
  }
}

function imageLoader (data) {
  var index = null
  var state = {
    sources: [],
    found: [],
    data: {}
  }

  if (data.object.tags.image) {
    img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      id = img.substr(5)
      state.found.push(id)
      state.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(39))
      state.found.push(id)
      state.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(40))
      state.found.push(id)
      state.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else {
      state.found.push(img)
      state.data[img] = {
        id: id,
        type: 'url'
      }
    }
  }

  if (data.object.tags.wikidata) {
    state.sources.push({
      type: 'wikidata',
      value: data.object.tags.wikidata
    })
  }

  if (data.object.tags.wikimedia_commons) {
    state.sources.push({
      type: 'wikimedia_commons',
      value: data.object.tags.wikimedia_commons,
    })
  }

  function loadWikidata (src, callback) {
    var value = src.value

    wikidata.load(value, function (err, result) {
      if (result && result.claims && result.claims.P18) {
        result.claims.P18.forEach(function (d) {
          id = d.mainsnak.datavalue.value

          if (state.found.indexOf(id) === -1) {
            state.found.push(id)
            state.data[id] = {
              id: id,
              type: 'wikimedia'
            }
          }
        })
      }

      handlePending()
    })
  }

  function loadWikimediaCommons (src, callback) {
    var value = src.value

    if (value.substr(0, 9) === 'Category:') {
      ajax('wikimedia', { page: value }, function (result) {
        if (result.images) {
          result.images.forEach(function (d) {
            if (state.found.indexOf(d) === -1) {
              state.found.push(d)
	      state.data[d] = {
		id: d,
		type: 'wikimedia'
	      }
            }
          })
        }

        handlePending()
      })
    } else if (value.substr(0, 5) === 'File:') {
      var id = value.substr(5)
      if (state.found.indexOf(id) === -1) {
        state.found.push(id)
        state.data[id] = {
          id: id,
          type: 'wikimedia'
        }
      }

      handlePending()
    }
  }

  function handlePending () {
    var pending = state.pendingCallbacks
    delete state.pendingCallbacks

    pending.forEach(function (c) {
      callbackCurrent(c)
    })
  }

  function callbackCurrent (callback) {
    if (index < state.found.length) {
      return callback(null, state.data[state.found[index]])
    }

    if (state.pendingCallbacks) {
      state.pendingCallbacks.push(callback)
      return
    }

    if (state.sources.length) {
      var src = state.sources.shift()
      state.pendingCallbacks = [ callback ]

      if (src.type === 'wikimedia_commons') {
        loadWikimediaCommons(src, handlePending)
      } else if (src.type === 'wikidata') {
        loadWikidata(src, handlePending)
      }

      return
    }

    callback(null, null)
  }

  return {
    first: function (callback) {
      index = 0

      callbackCurrent(callback)
    },

    next: function (callback) {
      if (index === null) {
        index = 0
      } else {
        index ++
      }

      callbackCurrent(callback)
    }
  }
}
window.imageLoader = imageLoader

register_hook('show-details', function (data, category, dom, callback) {
  var found = 0
  var div = document.createElement('div')
  div.className = 'images loading'

  dom.appendChild(div)

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  imageLoadAll(data,
    function (err, img) {
      if (found === 0) {
        h = document.createElement('h3')
        h.appendChild(document.createTextNode(lang('images')))
        div.insertBefore(h, div.firstChild)
      }

      found++

      show(img, {}, div)
    },
    function (err) {
      div.classList.remove('loading')
      callback(err)
    }
  )
})
