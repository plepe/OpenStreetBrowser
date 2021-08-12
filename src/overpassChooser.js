/* globals overpassUrl:true */
const hooks = require('modulekit-hooks')

hooks.register('init', function () {
  if (options.overpassUrl) {
    overpassUrl = options.overpassUrl
  }
})

hooks.register('options_form', function (def) {
  var values = config.overpassUrl
  if (!Array.isArray(values)) {
    values = [ values ]
  }

  def.overpassUrl = {
    'name': lang('options:overpassUrl'),
    'type': 'select',
    'values': values,
    'req': false,
    'placeholder': lang('default')
  }
})

hooks.register('options_save', function (data) {
  if ('overpassUrl' in data) {
    if (data.overpassUrl === null) {
      overpassUrl = config.overpassUrl
      if (Array.isArray(overpassUrl) && overpassUrl.length) {
        overpassUrl = overpassUrl[0]
      }
    } else {
      overpassUrl = data.overpassUrl
    }

    overpassFrontend.url = overpassUrl
  }
})
