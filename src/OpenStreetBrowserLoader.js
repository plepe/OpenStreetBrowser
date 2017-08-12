var OverpassLayer = require('overpass-layer')

function OpenStreetBrowserLoader () {
  this.types = {}
  this.categories = {}
  this.templates = {}
}

OpenStreetBrowserLoader.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserLoader.prototype.getCategory = function (id, callback) {
  if (id in this.categories) {
    callback(null, this.categories[id])
    return
  }

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
    }.bind(this))

  }

  var req = new XMLHttpRequest()
  req.addEventListener("load", reqListener.bind(this, req))
  req.open("GET", config.categoriesDir + '/' + id + ".json?" + config.categoriesRev)
  req.send()

}

OpenStreetBrowserLoader.prototype.getTemplate = function (id, callback) {
  if (id in this.templates) {
    callback(null, this.templates[id])
    return
  }

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      return callback(req.statusText, null)
    }

    this.templates[id] = OverpassLayer.twig.twig({ data: req.responseText, autoescape: true })

    callback(null, this.templates[id])
  }

  var req = new XMLHttpRequest()
  req.addEventListener("load", reqListener.bind(this, req))
  req.open("GET", config.categoriesDir + '/' + id + ".html?" + config.categoriesRev)
  req.send()

}

OpenStreetBrowserLoader.prototype.getCategoryFromData = function (id, data, callback) {
  if (!data.type) {
    callback('no type defined', null)
    return
  } else if (!(data.type in this.types)) {
    callback('unknown type', null)
    return
  } else {
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
}

OpenStreetBrowserLoader.prototype.forget = function (id) {
  this.categories[id].remove()
  delete this.categories[id]
}

OpenStreetBrowserLoader.prototype.registerType = function (type, classObject) {
  this.types[type] = classObject
}

module.exports = new OpenStreetBrowserLoader()
