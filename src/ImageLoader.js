const async = require('async')
var wikidata = require('./wikidata')
var wikipedia = require('./wikipedia')
var cache = {}

function ImageLoader (data) {
  this.sources = []
  this.found = []
  this.data = {}
  this.defaultCounter = {}

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

  if (data.object.tags.wikipedia) {
    this.sources.push({
      type: 'wikipedia',
      value: data.object.tags.wikipedia
    })
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

  call_hooks('image_sources', this.sources, data)

  cache[data.id] = this
}

ImageLoader.prototype.loadWikidata = function (src, callback) {
  var value = src.value

  wikidata.load(value, (err, result) => {
    async.series([
      (done) => {
        if (result && result.claims && result.claims.P18) {
          result.claims.P18.forEach((d) => {
            let id = d.mainsnak.datavalue.value

            if (this.found.indexOf(id) === -1) {
              this.found.push(id)
              this.data[id] = {
                id: id,
                type: 'wikimedia'
              }
            }
          })
        }

        done(null)
      },
      (done) => {
        // wikimedia commons
        if (result && result.claims && result.claims.P373) {
          result.claims.P373.forEach((d) => {
            let value = 'Category:' + d.mainsnak.datavalue.value

            this.sources.push({
              type: 'wikimedia_commons',
              value
            })
          })
        }

        done(null)
      }
    ], (err) => {
      callback(err)
    })
  })
}

ImageLoader.prototype.loadWikimediaCommons = function (src, callback) {
  var value = src.value

  if (value.substr(0, 9) === 'Category:') {
    var param = { page: value }
    if (src.continue) {
      param.continue = src.continue
    }

    ajax('ImageLoaderWikimediaCategoryList', param, function (result) {
      if (result.imageData) {
        result.imageData.forEach(function (d) {
          if (this.found.indexOf(d.id) === -1) {
            this.found.push(d.id)
            d.type = 'wikimedia'
            this.data[d.id] = d
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

ImageLoader.prototype.loadWikipedia = function (src, callback) {
  var value = src.value

  wikipedia.getImages(value, function (err, result) {
    if (err) {
      return callback(err, null)
    }

    result.forEach(function (d) {
      if (this.found.indexOf(d) === -1) {
        this.found.push(d.id)
        d.type = 'wikimedia'
        this.data[d.id] = d
      }
    }.bind(this))

    callback(null)
  }.bind(this))
}

ImageLoader.prototype.loadFun = function (src, callback) {
  src.fun((err, file) => {
    if (file) {
      this.found.push(file.id)
      this.data[file.id] = file
    }

    callback(null)
  })
}

ImageLoader.prototype.handlePending = function () {
  var pending = this.pendingCallbacks
  delete this.pendingCallbacks

  pending.forEach(function (c) {
    this.callbackCurrent.apply(this, c)
  }.bind(this))
}

ImageLoader.prototype.callbackCurrent = function (index, options, callback) {
  if (index < this.found.length) {
    return callback(null, this.data[this.found[index]])
  }

  if (this.pendingCallbacks) {
    this.pendingCallbacks.push([ index, options, callback ])
    return
  }

  if (this.sources.length) {
    var src = this.sources.shift()
    this.pendingCallbacks = [ [ index, options, callback ] ]

    if (src.type === 'wikimedia_commons') {
      this.loadWikimediaCommons(src, this.handlePending.bind(this))
    } else if (src.type === 'wikidata') {
      this.loadWikidata(src, this.handlePending.bind(this))
    } else if (src.type === 'wikipedia') {
      this.loadWikipedia(src, this.handlePending.bind(this))
    } else if (src.type === 'fun') {
      this.loadFun(src, this.handlePending.bind(this))
    }

    return
  }

  if (options.wrap && this.found.length) {
    var counter = this.defaultCounter
    if ('counter' in options) {
      counter = options.counter
    }
    counter.index = index - this.found.length

    return this.callbackCurrent(counter.index, options, callback)
  }

  callback(null, null)
}

/* options:
 * - wrap: whether to wrap to the first image after last (true/false)
 * - counter: use a different counter object (pass an empty object)
 */
ImageLoader.prototype.first = function (options, callback) {
  var counter = this.defaultCounter
  if ('counter' in options) {
    counter = options.counter
  }
  counter.index = 0

  this.callbackCurrent(counter.index, options, callback)
}

ImageLoader.prototype.next = function (options, callback) {
  var counter = this.defaultCounter
  if ('counter' in options) {
    counter = options.counter
  }

  if (!('index' in counter) || counter.index === null) {
    counter.index = 0
  } else {
    counter.index ++
  }

  this.callbackCurrent(counter.index, options, callback)
}

module.exports = ImageLoader
