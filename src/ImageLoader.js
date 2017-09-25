var wikidata = require('./wikidata')
var cache = {}

function ImageLoader (data) {
  if (this === window) {
    if (data.id in cache) {
      return cache[data.id]
    }

    return new ImageLoader(data)
  }

  this.index = null
  this.sources = []
  this.found = []
  this.data = {}

  this.parseObject(data)
}

ImageLoader.prototype.parseObject = function (data) {
  var img
  var id

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
      value: data.object.tags.wikimedia_commons
    })
  }

  cache[data.id] = this
}

ImageLoader.prototype.loadWikidata = function (src, callback) {
  var value = src.value

  wikidata.load(value, function (err, result) {
    if (result && result.claims && result.claims.P18) {
      result.claims.P18.forEach(function (d) {
        var id = d.mainsnak.datavalue.value

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

ImageLoader.prototype.loadWikimediaCommons = function (src, callback) {
  var value = src.value

  if (value.substr(0, 9) === 'Category:') {
    var param = { page: value }
    if (src.continue) {
      param.continue = src.continue
    }

    ajax('wikimedia', param, function (result) {
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

      if (result.continue) {
        this.sources.push({
          type: 'wikimedia_commons',
          value: value,
          continue: result.continue
        })
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

ImageLoader.prototype.handlePending = function () {
  var pending = this.pendingCallbacks
  delete this.pendingCallbacks

  pending.forEach(function (c) {
    this.callbackCurrent.apply(this, c)
  }.bind(this))
}

ImageLoader.prototype.callbackCurrent = function (index, callback, wrap) {
  if (index < this.found.length) {
    return callback(null, this.data[this.found[index]])
  }

  if (this.pendingCallbacks) {
    this.pendingCallbacks.push([ index, callback, wrap ])
    return
  }

  if (this.sources.length) {
    var src = this.sources.shift()
    this.pendingCallbacks = [ [ index, callback, wrap ] ]

    if (src.type === 'wikimedia_commons') {
      this.loadWikimediaCommons(src, this.handlePending.bind(this))
    } else if (src.type === 'wikidata') {
      this.loadWikidata(src, this.handlePending.bind(this))
    }

    return
  }

  if (wrap && this.found.length) {
    return this.callbackCurrent(index - this.found.length, callback)
  }

  callback(null, null)
}

ImageLoader.prototype.first = function (callback) {
  this.index = 0

  this.callbackCurrent(this.index, callback)
}

ImageLoader.prototype.next = function (callback) {
  if (this.index === null) {
    this.index = 0
  } else {
    this.index ++
  }

  this.callbackCurrent(this.index, callback)
}

ImageLoader.prototype.nextWrap = function (callback) {
  if (this.index === null) {
    this.index = 0
  } else {
    this.index ++
  }

  this.callbackCurrent(this.index, callback, true)
}

module.exports = ImageLoader
