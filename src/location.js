var ipLocation = require('./ip-location')

register_hook('init_callback', function (callback) {
  ipLocation('', function (err, ipLoc) {
    if (typeof ipLoc === 'object' && 'latitude' in ipLoc) {
      map.setView([ ipLoc.latitude, ipLoc.longitude ], 14)
    } else {
      map.setView(config.defaultView, 'zoom' in config.defaultView ? config.defaultView.zoom : 14)
    }

    callback()
  })
})
