var OverpassLayer = require('overpass-layer')
var jsonMultilineStrings = require('json-multiline-strings')

function OpenStreetBrowserLoader () {
  this.types = {}
  this.categories = {}
  this.templates = {}
  this._loadClash = {} // if a category is being loaded multiple times, collect callbacks
}

OpenStreetBrowserLoader.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserLoader.prototype.getCategory = function (id, callback) {
  if (id in this.categories) {
    callback(null, this.categories[id])
    return
  }

  if (id in this._loadClash) {
    this._loadClash[id].push(callback)
    return
  }

  this._loadClash[id] = []

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      return callback(req.statusText, null)
    }

    var data = JSON.parse(req.responseText)

    this.getCategoryFromData(id, data, function (err, category) {
      if (category) {
        category.setMap(this.map)
      }

      callback(err, category)

      this._loadClash[id].forEach(function (c) {
        c(err, category)
      })
      delete this._loadClash[id]
    }.bind(this))
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', reqListener.bind(this, req))
  req.open('GET', 'categories.php?id=' + id + '&' + config.categoriesRev)
  req.send()
}

OpenStreetBrowserLoader.prototype.getTemplate = function (id, callback) {
  if (id in this.templates) {
    callback.apply(this, this.templates[id])
    return
  }

  if (id in this._loadClash) {
    this._loadClash[id].push(callback)
    return
  }

  this._loadClash[id] = []

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      this.templates[id] = [ req.statusText, null ]
    } else {
      this.templates[id] = [ null, OverpassLayer.twig.twig({ data: req.responseText, autoescape: true }) ]
    }

    callback.apply(this, this.templates[id])

    this._loadClash[id].forEach(function (c) {
      c.apply(this, this.templates[id])
    }.bind(this))
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', reqListener.bind(this, req))
  req.open('GET', config.categoriesDir + '/' + id + '.html?' + config.categoriesRev)
  req.send()
}

OpenStreetBrowserLoader.prototype.getCategoryFromData = function (id, data, callback) {
  if (!data.type) {
    return callback(new Error('no type defined'), null)
  }

  if (!(data.type in this.types)) {
    return callback(new Error('unknown type'), null)
  }

  var layer = new this.types[data.type](id, data)

  layer.setMap(this.map)

  this.categories[id] = layer

  if ('load' in layer) {
    layer.load(function (err) {
      callback(err, layer)
    })
  } else {
    callback(null, layer)
  }
}

OpenStreetBrowserLoader.prototype.forget = function (id) {
  this.categories[id].remove()
  delete this.categories[id]
}

OpenStreetBrowserLoader.prototype.registerType = function (type, classObject) {
  this.types[type] = classObject
}

module.exports = new OpenStreetBrowserLoader()
