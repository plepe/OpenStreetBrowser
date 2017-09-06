var ipLocation = require('./ip-location')

register_hook('init_callback', function (callback) {
  if ('checkIpLocation' in config && !config.checkIpLocation) {
    map.setView(config.defaultView, 'zoom' in config.defaultView ? config.defaultView.zoom : 14)
    return callback()
  }

  ipLocation('', function (err, ipLoc) {
    // initial map location already set
    if (typeof map.getZoom() !== 'undefined') {
      return callback()
    }

    if (typeof ipLoc === 'object' && 'latitude' in ipLoc) {
      map.setView([ ipLoc.latitude, ipLoc.longitude ], 14)
    } else {
      map.setView(config.defaultView, 'zoom' in config.defaultView ? config.defaultView.zoom : 14)
    }

    callback()
  })
})
