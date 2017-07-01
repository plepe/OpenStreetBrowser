var tagTranslations = require('./tagTranslations')

register_hook('init_callback', function (callback) {
  tagTranslations.setTagLanguage(ui_lang)
  tagTranslations.load('node_modules/openstreetmap-tag-translations', ui_lang, function (err) {
    if (err) {
      err = 'Error loading translations: ' + err
    }

    callback(err)
  })
})

register_hook('options_form', function (def) {
  def.ui_lang = {
    'name': 'UI Language',
    'type': 'text'
  }
})
