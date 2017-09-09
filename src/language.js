/* global languages:false, lang_str:false */
var tagTranslations = require('./tagTranslations')

function getPreferredDataLanguage () {
  var m = (navigator.language || navigator.userLanguage).match(/^([^-]+)(-.*|)$/)
  if (m) {
    return m[1].toLocaleLowerCase()
  } else {
    return ui_lang
  }
}

function getAcceptLanguages () {
  return navigator.languages || [ navigator.language || navigator.userLanguage ]
}

function getUiLanguages () {
  var i, code
  var ret = {}
  var acceptLanguages = getAcceptLanguages()

  for (i = 0; i < acceptLanguages.length; i++) {
    code = acceptLanguages[i]
    if (languages.indexOf(code) !== -1) {
      ret[code] = langName(code)
    }
  }

  for (i = 0; i < languages.length; i++) {
    code = languages[i]
    if (!(code in ret)) {
      ret[code] = langName(code)
    }
  }

  return ret
}

function getDataLanguages () {
  var code
  var ret = {}
  var acceptLanguages = getAcceptLanguages()

  for (var i = 0; i < acceptLanguages.length; i++) {
    code = acceptLanguages[i]
    ret[code] = langName(code)
  }

  for (var k in lang_str) {
    var m = k.match(/^lang:(.*)$/)
    if (m) {
      code = m[1]
      if (code === 'current') {
        continue
      }
      if (!(code in ret)) {
        ret[code] = langName(code)
      }
    }
  }

  return ret
}

function langName (code) {
  var ret = ''

  if (('lang_native:' + code) in lang_str && lang_str['lang_native:' + code]) {
    ret += lang_str['lang_native:' + code]
  } else {
    ret += 'Language "' + code + '"'
  }

  if (('lang:' + code) in lang_str && lang_str['lang:' + code]) {
    ret += ' (' + lang_str['lang:' + code] + ')'
  }

  return ret
}

register_hook('init_callback', function (initState, callback) {
  if ('data_lang' in options) {
    tagTranslations.setTagLanguage(options.data_lang)
  } else {
    tagTranslations.setTagLanguage(getPreferredDataLanguage())
  }

  callback(null)
})

register_hook('options_form', function (def) {
  def.ui_lang = {
    'name': lang('options:ui_lang'),
    'type': 'select',
    'values': getUiLanguages(),
    'req': true,
    'default': ui_lang,
    'reloadOnChange': true
  }

  def.data_lang = {
    'name': lang('options:data_lang'),
    'desc': lang('options:data_lang:desc'),
    'type': 'select',
    'values': getDataLanguages(),
    'default': getPreferredDataLanguage(),
    'placeholder': lang('options:data_lang:local')
  }
})

register_hook('options_save', function (data) {
  if ('data_lang' in data) {
    if ('data_lang' in options && options.data_lang !== data.data_lang) {
      tagTranslations.setTagLanguage(data.data_lang)
      baseCategory.recalc()
    }
  }
})
