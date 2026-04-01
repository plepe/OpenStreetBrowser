const OverpassFrontend = require('@geowiki-net/geowiki-api')

global.overpassUrl = null
global.overpassFrontend = null
global.geowikiAPI = null

module.exports = {
  id: 'geowikiAPI',
  appInit (app) {
    app.on('init', () => {
      if (!global.overpassUrl) {
        global.overpassUrl = app.config.overpassUrl
        if (Array.isArray(global.overpassUrl) && global.overpassUrl.length) {
          overpassUrl = overpassUrl[0]
        }
      }

      global.overpassFrontend = new OverpassFrontend(overpassUrl, config.overpassOptions)
      global.geowikiAPI = overpassFrontend
    })
  }
}
