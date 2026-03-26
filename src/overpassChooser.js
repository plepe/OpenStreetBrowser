/* globals overpassUrl:true */
var OverpassFrontend = require('@geowiki-net/geowiki-api')
var overpassChosenFrontends = {}

register_hook('init', function () {
  if (options.overpassUrl) {
    overpassUrl = options.overpassUrl
  }
})

register_hook('options_form', function (def) {
  var _values = config.overpassUrl
  if (!Array.isArray(config.overpassUrl)) {
    _values = [ _values ]
  }

  var values = {}
  _values.forEach(k => values[k] = k)

  values['_upload'] = lang('options:overpassUrl:upload')

  def.overpassUrl = {
    'name': lang('options:overpassUrl'),
    'desc': lang('options:overpassUrl:info'),
    'type': 'select_other',
    'values': values,
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
    if (!(overpassFrontend.url in overpassChosenFrontends)) {
      overpassChosenFrontends[overpassFrontend.url] = global.overpassFrontend
    }

    const overpassUrl = data.overpassUrl

    if (overpassUrl === '_upload' && data.overpassUrlUpload) {
      const options = { ...config.overpassOptions, filename: data.overpassUrlUpload.name }
      overpassChosenFrontends[overpassUrl] = new OverpassFrontend(data.overpassUrlUpload.url, config.overpassOptions)
    }
    else if (!(overpassUrl in overpassChosenFrontends)) {
      overpassChosenFrontends[overpassUrl] = new OverpassFrontend(overpassUrl, config.overpassOptions)
    }

    global.overpassFrontend = overpassChosenFrontends[overpassUrl]

    call_hooks('overpass-server-changed')
  }
})
