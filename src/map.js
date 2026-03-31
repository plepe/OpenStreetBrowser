const mapMetersPerPixel = require('./map-getMetersPerPixel')
const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

let map

module.exports = {
  id: 'map',
  appInit (app) {
    app.on('init', state => {
      const mapOptions = app.config.mapOptions || {}

      if (app.config.maxZoom) {
        mapOptions.maxZoom = app.config.maxZoom
      }

      app.emit('map-options', mapOptions)

      map = L.map('map', mapOptions)
      app.map = map
      global.map = map

      app.emit('map-init', map)

      OpenStreetBrowserLoader.setMap(app.map)

      map.getMetersPerPixel = mapMetersPerPixel.bind(map)

      map.attributionControl.setPrefix('<a target="_blank" href="https://wiki.openstreetmap.org/wiki/OpenStreetBrowser">OpenStreetBrowser</a>')

      // Scale bar
      L.control.scale().addTo(map)

      map.createPane('selected')
      map.getPane('selected').style.zIndex = 498
      map.createPane('casing')
      map.getPane('casing').style.zIndex = 399

      map.on('moveend', function (e) {
        app.state.updateLink()
      })
    })

    app.on('state-apply', state => {
      // location
      if (state.lat && state.lon && state.zoom) {
        if (typeof map.getZoom() === 'undefined') {
          map.setView({ lat: state.lat, lng: state.lon }, state.zoom)
        } else {
          map.flyTo({ lat: state.lat, lng: state.lon }, state.zoom)
        }
      }
    })
  
    app.on('state-get', state => {
      // location
      if (typeof map.getZoom() !== 'undefined') {
        var center = map.getCenter().wrap()
        var zoom = map.getZoom()

        state.lat = center.lat
        state.lon = center.lng
        state.zoom = zoom
      }
    })
  }
}
