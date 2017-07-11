var tagTranslations = require('./tagTranslations')

register_hook('init_callback', function (callback) {
  tagTranslations.setTagLanguage(ui_lang)
  callback(null)
})

register_hook('options_form', function (def) {
  var languages = {}
  for (var k in lang_str) {
    var m
    if (m = k.match(/^lang:(.*)$/)) {
      if (m[1] === 'current') {
        continue
      }

      languages[m[1]] = lang_str['lang_native:' + m[1]] + ' (' + lang_str[k] + ')'
    }
  }

  def.ui_lang = {
    'name': 'UI Language',
    'type': 'select',
    'values': languages,
    'req': true,
    'default': ui_lang
  }
})

register_hook('options_save', function (data) {
  if ('ui_lang' in data && data.ui_lang !== ui_lang) {
    location.reload()
  }
})
