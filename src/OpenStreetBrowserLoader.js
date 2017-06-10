function OpenStreetBrowserLoader () {
  this.types = {}
}


OpenStreetBrowserLoader.prototype.load = function (id, callback) {
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
