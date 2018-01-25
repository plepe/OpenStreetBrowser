function maki (file, options, callback) {
  var result = (typeof openstreetbrowserPrefix === 'undefined' ? './' : openstreetbrowserPrefix) +
    'node_modules/@mapbox/maki/icons/' + file + '.svg'
  callback(null, result)
}

module.exports = maki
