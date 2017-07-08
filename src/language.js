var tagTranslations = require('./tagTranslations')

register_hook('init_callback', function (callback) {
  tagTranslations.setTagLanguage(ui_lang)
  callback(null)
})

register_hook('options_form', function (def) {
  def.ui_lang = {
    'name': 'UI Language',
    'type': 'text'
  }
})
