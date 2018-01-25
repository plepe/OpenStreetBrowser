var loadClash = {}
var cache = {}

function applyOptions (code, options) {
  var style = ''

  for (var k in options) {
    if (k !== 'size') {
      style += k + ':' + options[k] + ';'
    }
  }

  return code.replace('path d=', 'path style="' + style + '" d=')
}

function maki (file, options, callback) {
  var id
  var size = options.size || 15

  var m = file.match(/^(.*)\-(11|15)/)
  if (!m) {
    file += '-' + size
  }

  var url = (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
    'node_modules/@mapbox/maki/icons/' + file + '.svg'

  if (file in cache) {
    return callback(null, 'data:image/svg+xml;utf8,' + applyOptions(cache[file], options))
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

    loadClash[file].forEach(p => p[1](null, 'data:image/svg+xml;utf8,' + applyOptions(cache[file], p[0])))
    delete loadClash[file]
    return
  })
  req.open('GET', url)
  req.send()
}

module.exports = maki
