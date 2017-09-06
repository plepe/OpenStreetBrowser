var ipLocation = require('./ip-location')

register_hook('init_callback', function (initState, callback) {
  initState.map = ('zoom' in config.defaultView ? config.defaultView.zoom : 14) + '/' + config.defaultView.lat + '/' + config.defaultView.lon

  if ('checkIpLocation' in config && !config.checkIpLocation) {
    return callback()
  }

  ipLocation('', function (err, ipLoc) {
    var ret

    if (typeof ipLoc === 'object' && 'latitude' in ipLoc) {
      initState.map = '14/' + ipLoc.latitude + '/' + ipLoc.longitude
    }

    callback()
  })
})
