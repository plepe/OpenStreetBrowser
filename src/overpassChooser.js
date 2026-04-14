/* globals overpassUrl:true */
var OverpassFrontend = require('@geowiki-net/geowiki-api')
var geowikiAPI = require('./geowikiAPI')
var overpassChosenFrontends = null
let uploaded = 0

module.exports = {
  id: 'overpassChooser',
  appInit (app) {
    if (options.overpassUrl) {
      global.overpassUrl = options.overpassUrl
    }
  }
}

register_hook('options_form', function (def) {
  var values = {}
  geowikiAPI.list.forEach(entry => {
    values[entry.id] = entry
  })

  values['_upload'] = lang('options:overpassUrl:upload')

  def.overpassUrl = {
    'name': lang('options:overpassUrl'),
    'desc': lang('options:overpassUrl:info'),
    'type': 'select_other',
    'values': values,
    'values_mode': 'keys',
    'placeholder': lang('default'),
    'button:other': lang('options:overpassUrl:custom'),
    'other_def': {
      type: 'text',
      placeholder: 'https://....',
    }
  }

  def.overpassUrlUpload = {
    'type': 'file',
    'name': lang('options:overpassUrl:upload'),
    'desc': lang('options:overpassUrl:upload:info'),
    'show_depend': ['check', 'overpassUrl', ['is', '_upload']],
  }
})

register_hook('options_save', function (data) {
  if ('overpassUrl' in data) {
    if (!overpassChosenFrontends) {
      overpassChosenFrontends = {}
      overpassChosenFrontends[geowikiAPI.list[0].id] = global.geowikiAPI
    }

    let id = data.overpassUrl
    let entry

    if (id === '_upload') {
      if (data.overpassUrlUpload) {
        entry = {
          id: '_upload' + (uploaded++),
          name: data.overpassUrlUpload.name,
          url: data.overpassUrlUpload.url,
          options: { filename: data.overpassUrlUpload.name }
        }

        id = entry.id
        geowikiAPI.list.push(entry)
      }
    } else {
      const chosen = geowikiAPI.list.filter(entry => entry.id === id)
      if (!chosen.length) {
        console.log('not found')
        return
      }

      entry = chosen[0]
    }

    if (id in overpassChosenFrontends) {
      global.overpassFrontend = overpassChosenFrontends[id]
    } else {
      const api = new OverpassFrontend(entry.url, { ...config.overpassOptions, ...entry.options })
      overpassChosenFrontends[id] = api
      global.overpassFrontend = api
    }

    call_hooks('overpass-server-changed')
  }
})
