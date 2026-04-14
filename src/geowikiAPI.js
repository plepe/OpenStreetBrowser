const OverpassFrontend = require('@geowiki-net/geowiki-api')

global.overpassUrl = null
global.overpassFrontend = null
global.geowikiAPI = null

const list = []

module.exports = {
  id: 'geowikiAPI',
  list,
  appInit (app) {
    app.on('init', () => {
      if (typeof app.config.overpassUrl === 'string') {
        list.push({
          id: app.config.overpassUrl,
          name: app.config.overpassUrl,
          url: app.config.overpassUrl
        })
      }
      else if (Array.isArray(app.config.overpassUrl) && app.config.overpassUrl.length) {
        app.config.overpassUrl.forEach(entry => {
          if (typeof entry === 'string') {
            list.push({
              id: entry,
              name: entry,
              url: entry
            })
          } else {
            if (!entry.id) { entry.id = entry.url }
            if (!entry.name) { entry.name = entry.url }

            list.push(entry)
          }
        })
      }

      let entry = list[0]
      if (global.overpassUrl) {
        const chosen = list.filter(entry => entry.url === global.overpassUrl)
        if (!chosen.length) {
          console.log(global.overpassUrl + ' not found')
          entry = list[0]
        } else {
          entry = chosen[0]
        }
      }

      global.overpassFrontend = new OverpassFrontend(entry.url, { ...config.overpassOptions, ...(entry.options ?? {}) })
      global.geowikiAPI = global.overpassFrontend
    })
  }
}
