/* globals overpassUrl:true */

register_hook('init', function () {
  if (options.overpassUrl) {
    overpassUrl = options.overpassUrl
  }
})

register_hook('options_form', function (def) {
  var values = config.overpassUrl
  if (!Array.isArray(values)) {
    values = [ values ]
  }

  def.overpassUrl = {
    'name': lang('options:overpassUrl'),
    'desc': lang('options:overpassUrl:info'),
    'type': 'select_other',
    'values': values,
    'placeholder': lang('default'),
    'reloadOnChange': true,
    'button:other': lang('options:overpassUrl:custom'),
    'other_def': {
      type: 'text',
      placeholder: 'https://....',
    }
  }
})

register_hook('options_save', function (data) {
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
