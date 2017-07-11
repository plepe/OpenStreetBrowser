var tagTranslations = require('./tagTranslations')

register_hook('init_callback', function (callback) {
  if ('data_lang' in options) {
    tagTranslations.setTagLanguage(options.data_lang)
  } else {
    tagTranslations.setTagLanguage(ui_lang)
  }

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

  def.data_lang = {
    'name': 'data language',
    'type': 'select',
    'values': languages,
    'default': ui_lang,
    'placeholder': 'Native language'
  }
})

register_hook('options_save', function (data) {
  if ('ui_lang' in data && data.ui_lang !== ui_lang) {
    location.reload()
  }

  if ('data_lang' in data) {
    if ('data_lang' in options && options.data_lang !== data.data_lang) {
      tagTranslations.setTagLanguage(data.data_lang)
      baseCategory.recalc()
    }
  }
})
