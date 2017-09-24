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
  if (this === window) {
    if (data.id in cache) {
      return cache[data.id]
    }

    return new imageLoader(data)
  }

  this.index = null
  this.sources = []
  this.found = []
  this.data = {}

  if (data.object.tags.image) {
    img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      id = img.substr(5)
      this.found.push(id)
      this.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(39)).replace(/_/g, ' ')
      this.found.push(id)
      this.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(40)).replace(/_/g, ' ')
      this.found.push(id)
      this.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    } else {
      this.found.push(img)
      this.data[img] = {
        id: img,
        type: 'url'
      }
    }
  }

  if (data.object.tags.wikidata) {
    this.sources.push({
      type: 'wikidata',
      value: data.object.tags.wikidata
    })
  }

  if (data.object.tags.wikimedia_commons) {
    this.sources.push({
      type: 'wikimedia_commons',
      value: data.object.tags.wikimedia_commons,
    })
  }

  cache[data.id] = this
}

imageLoader.prototype.loadWikidata = function (src, callback) {
  var value = src.value

  wikidata.load(value, function (err, result) {
    if (result && result.claims && result.claims.P18) {
      result.claims.P18.forEach(function (d) {
        id = d.mainsnak.datavalue.value

        if (this.found.indexOf(id) === -1) {
          this.found.push(id)
          this.data[id] = {
            id: id,
            type: 'wikimedia'
          }
        }
      }.bind(this))
    }

    callback(err)
  }.bind(this))
}

imageLoader.prototype.loadWikimediaCommons = function (src, callback) {
  var value = src.value

  if (value.substr(0, 9) === 'Category:') {
    ajax('wikimedia', { page: value }, function (result) {
      if (result.images) {
        result.images.forEach(function (d) {
          if (this.found.indexOf(d) === -1) {
            this.found.push(d)
	    this.data[d] = {
	      id: d,
	      type: 'wikimedia'
	    }
          }
        }.bind(this))
      }

      callback(null)
    }.bind(this))
  } else if (value.substr(0, 5) === 'File:') {
    var id = value.substr(5)
    if (this.found.indexOf(id) === -1) {
      this.found.push(id)
      this.data[id] = {
        id: id,
        type: 'wikimedia'
      }
    }

    callback(null)
  } else {
    callback(new Error('Can\'t parse value'))
  }
}

imageLoader.prototype.handlePending = function () {
  var pending = this.pendingCallbacks
  delete this.pendingCallbacks

  pending.forEach(function (c) {
    this.callbackCurrent(c)
  }.bind(this))
}

imageLoader.prototype.callbackCurrent = function (callback, wrap) {
  if (this.index < this.found.length) {
    return callback(null, this.data[this.found[this.index]])
  }

  if (this.pendingCallbacks) {
    this.pendingCallbacks.push(callback)
    return
  }

  if (this.sources.length) {
    var src = this.sources.shift()
    this.pendingCallbacks = [ callback ]

    if (src.type === 'wikimedia_commons') {
      this.loadWikimediaCommons(src, this.handlePending.bind(this))
    } else if (src.type === 'wikidata') {
      this.loadWikidata(src, this.handlePending.bind(this))
    }

    return
  }

  if (wrap && this.found.length) {
    this.index = 0
    return this.callbackCurrent(callback)
  }

  callback(null, null)
}

imageLoader.prototype.first = function (callback) {
  this.index = 0

  this.callbackCurrent(callback)
}

imageLoader.prototype.next = function (callback) {
  if (this.index === null) {
    this.index = 0
  } else {
    this.index ++
  }

  this.callbackCurrent(callback)
}

imageLoader.prototype.nextWrap = function (callback) {
  if (this.index === null) {
    this.index = 0
  } else {
    this.index ++
  }

  this.callbackCurrent(callback, true)
}

window.imageLoader = imageLoader

register_hook('show-details', function (data, category, dom, callback) {
  var found = 0
  var div = document.createElement('div')
  div.className = 'images loading'
  var imageWrapper

  dom.appendChild(div)

  if (showTimer) {
    window.clearInterval(showTimer)
  }

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  var currentLoader = imageLoader(data)

  currentLoader.nextWrap(function (err, img) {
    div.classList.remove('loading')

    if (!img) {
      return callback(err)
    }

    h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('images')))
    div.insertBefore(h, div.firstChild)

    imageWrapper = document.createElement('div')
    imageWrapper.className = 'imageWrapper'
    div.appendChild(imageWrapper)

    showTimer = window.setInterval(loadNext, 5000)

    show(img, {}, imageWrapper)
  })

  function loadNext () {
    currentLoader.nextWrap(function (err, img) {
      show(img, {}, imageWrapper)
    })
  }
})

register_hook('hide-details', function () {
  if (showTimer) {
    window.clearInterval(showTimer)
  }
})
