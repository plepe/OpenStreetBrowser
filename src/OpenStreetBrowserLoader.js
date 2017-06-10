var OpenStreetBrowserIndex = require('./OpenStreetBrowserIndex')
var OpenStreetBrowserCategory = require('./OpenStreetBrowserCategory')

function OpenStreetBrowserLoader (id, callback) {
  function reqListener (req) {
    var data = JSON.parse(req.responseText)

    if (data.type && data.type === 'index') {
      var layer = new OpenStreetBrowserIndex(id, data)
    } else {
      var layer = new OpenStreetBrowserCategory(id, data)
    }

    callback(null, layer)
  }

  var req = new XMLHttpRequest()
  req.addEventListener("load", reqListener.bind(this, req))
  req.open("GET", "categories/" + id + ".json")
  req.send()
}

module.exports = OpenStreetBrowserLoader
