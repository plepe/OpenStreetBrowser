var wikidata = require('./wikidata')
var cache = {}
var showTimer

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
function show(img, options, div) {
  div.innerHTML = ''

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
  var state = {
    index: null,
    sources: [],
    found: [],
    data: {}
  }

  if (data.id in cache) {
    state = cache[data.id]
  } else {
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
        id = decodeURIComponent(img.substr(39)).replace(/_/g, ' ')
        state.found.push(id)
        state.data[id] = {
          id: id,
          type: 'wikimedia'
        }
      } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
        id = decodeURIComponent(img.substr(40)).replace(/_/g, ' ')
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
  }

  cache[data.id] = state

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
    if (state.index < state.found.length) {
      return callback(null, state.data[state.found[state.index]])
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
      state.index = 0

      callbackCurrent(callback)
    },

    next: function (callback) {
      if (state.index === null) {
        state.index = 0
      } else {
        state.index ++
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
  var imageWrapper = document.createElement('div')
  imageWrapper.className = 'imageWrapper'

  dom.appendChild(div)

  if (showTimer) {
    window.clearInterval(showTimer)
  }

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  div.appendChild(imageWrapper)

  var currentLoader = imageLoader(data)

  currentLoader.next(function (err, img) {
    div.classList.remove('loading')

    if (!img) {
      return callback(err)
    }

    h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('images')))
    div.insertBefore(h, div.firstChild)

    showTimer = window.setInterval(loadNext, 5000)

    show(img, {}, imageWrapper)
  })

  function loadNext () {
    currentLoader.next(function (err, img) {
      if (img === null) {
        currentLoader.first(function (err, img) {
          if (!img) {
            return window.clearInterval(timer)
          }

          show(img, {}, imageWrapper)
        })

        return
      }

      show(img, {}, imageWrapper)
    })
  }
})

register_hook('hide-details', function () {
  if (showTimer) {
    window.clearInterval(showTimer)
  }
})
