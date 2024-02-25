const svgToDataURI = require('mini-svg-data-uri')

/* global openstreetbrowserPrefix */
var loadClash = {}
var cache = {}
var paths = {
  maki: 'node_modules/@mapbox/maki/icons/ID.svg',
  temaki: 'node_modules/@rapideditor/temaki/icons/ID.svg'
}

function applyOptions (code, options) {
  var style = ''

  for (var k in options) {
    if (k !== 'size') {
      style += k + ':' + options[k] + ';'
    }
  }

  let result = code.replace(/<path/i, '<path style="' + style + '"')

  return svgToDataURI(result)
}

function maki (set, file, options, callback) {
  var m = file.match(/^(.*)/)
  if (m) {
    file = m[1]
    options.size = m[2]
  }

  var url = (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
    paths[set]
      .replace('ID', file)
      .replace('SIZE', options.size || 15)

  if (url in cache) {
    return callback(null, applyOptions(cache[url], options))
  }

  if (url in loadClash) {
    loadClash[url].push([ options, callback ])
    return
  } else {
    loadClash[url] = [ [ options, callback ] ]
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', function () {
    if (req.status !== 200) {
      loadClash[url].forEach(p => p[1](req.statusText, null))
      delete loadClash[url]
      return
    }

    cache[url] = req.responseText

    loadClash[url].forEach(p => p[1](null, applyOptions(cache[url], p[0])))
    delete loadClash[url]
  })
  req.open('GET', url)
  req.send()
}

module.exports = maki
