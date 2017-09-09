var ipLocation = require('./ip-location')

register_hook('init_callback', function (initState, callback) {
  if ('checkIpLocation' in config && !config.checkIpLocation) {
    return callback()
  }

  ipLocation('', function (err, ipLoc) {
    if (typeof ipLoc === 'object' && 'latitude' in ipLoc) {
      initState.zoom = 14
      initState.lat = ipLoc.latitude
      initState.lon = ipLoc.longitude
    }

    callback(err)
  })
})
