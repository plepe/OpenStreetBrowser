var loadClash = {}
var cache = {}

function maki (file, options, callback) {
  var url = (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
    'node_modules/@mapbox/maki/icons/' + file + '.svg'

  if (file in cache) {
    return callback(null, 'data:image/svg+xml;utf8,' + cache[file])
  }

  if (file in loadClash) {
    loadClash[file].push([ options, callback ])
    return
  } else {
    loadClash[file] = [ [ options, callback ] ]
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', function () {
    if (req.status !== 200) {
      loadClash[file].forEach(p => p[1](req.statusText, null))
      delete loadClash[file]
      return
    }

    cache[file] = req.responseText

    loadClash[file].forEach(p => p[1](null, 'data:image/svg+xml;utf8,' + cache[file]))
    delete loadClash[file]
    return
  })
  req.open('GET', url)
  req.send()
}

module.exports = maki
