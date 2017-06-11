function OpenStreetBrowserLoader () {
  this.types = {}
  this.categories = {}
}

OpenStreetBrowserLoader.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserLoader.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
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

    if (!data.type) {
      callback('no type defined', null)
      return
    } else if (!(data.type in this.types)) {
      callback('unknown type', null)
      return
    } else {
      var layer = new this.types[data.type](id, data)
    }

    layer.setMap(this.map)
    layer.setParentDom('category-' + id)

    this.categories[id] = layer

    callback(null, layer)
  }

  var req = new XMLHttpRequest()
  req.addEventListener("load", reqListener.bind(this, req))
  req.open("GET", "categories/" + id + ".json")
  req.send()
}

OpenStreetBrowserLoader.prototype.registerType = function (type, classObject) {
  this.types[type] = classObject
}

module.exports = new OpenStreetBrowserLoader()
