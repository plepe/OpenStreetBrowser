function OpenStreetBrowserLoader () {
  this.types = {}
  this.categories = {}
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
