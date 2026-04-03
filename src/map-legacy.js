const mapMetersPerPixel = require('./map-getMetersPerPixel')
const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

module.exports = {
  id: 'map-legacy',
  appInit (app) {
    app.on('map-init', map => {
      global.map = map

      OpenStreetBrowserLoader.setMap(app.map)

      app.map.getMetersPerPixel = mapMetersPerPixel.bind(app.map)

      app.map.attributionControl.setPrefix('<a target="_blank" href="https://wiki.openstreetmap.org/wiki/OpenStreetBrowser">OpenStreetBrowser</a>')

      // Scale bar
      L.control.scale().addTo(app.map)

      app.map.createPane('selected')
      app.map.getPane('selected').style.zIndex = 498
      app.map.createPane('casing')
      app.map.getPane('casing').style.zIndex = 399
    })
  }
}
