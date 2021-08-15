const ModulekitForm = require('modulekit-form')
const hooks = require('modulekit-hooks')
const ajax = require('modulekit-ajax')

/* globals options:true */
var moduleOptions = {}
var prevPage
var optionsFormEl

hooks.register('init', function () {
  var menu = document.getElementById('menu')

  var li = document.createElement('li')
  menu.appendChild(li)

  var link = document.createElement('a')
  link.innerHTML = lang('main:options')
  link.href = '#options'
  link.onclick = moduleOptions.open

  li.appendChild(link)
})

moduleOptions.open = function () {
  var def = {
    'debug': {
      'type': 'boolean',
      'name': lang('options:debug_mode'),
      'weight': 10,
      'reloadOnChange': true
    }
  }

  hooks.call('options_form', def)

  var optionsForm = new ModulekitForm('options', def)
  prevPage = document.getElementById('content').className
  document.getElementById('content').className = 'options'
  var dom = document.getElementById('contentOptions')
  dom.innerHTML = ''

  let orig_options = {
    debug: false
  }
  hooks.call('options_orig_data', orig_options)
  for (let k in orig_options) {
    if (!(k in options)) {
      options[k] = orig_options[k]
    }
  }

  optionsForm.set_data(options)

  optionsFormEl = document.createElement('form')
  optionsFormEl.onsubmit = moduleOptions.submit.bind(this, optionsForm)
  dom.appendChild(optionsFormEl)

  optionsForm.show(optionsFormEl)

  var input = document.createElement('button')
  input.innerHTML = lang('save')
  optionsFormEl.appendChild(input)

  input = document.createElement('button')
  input.innerHTML = lang('cancel')
  optionsFormEl.appendChild(input)
  input.onclick = function () {
    document.getElementById('content').className = prevPage
    dom.removeChild(optionsFormEl)
    return false
  }

  return false
}

moduleOptions.submit = function (optionsForm) {
  var data = optionsForm.get_data()

  var reload = false
  for (var k in data) {
    if (optionsForm.def[k].reloadOnChange && options[k] !== data[k]) {
      reload = true
    }
  }

  ajax('options_save', null, data, function (ret) {
    let oldOptions = options
    options = data

    optionsFormEl.parentNode.removeChild(optionsFormEl)

    document.getElementById('content').className = prevPage

    hooks.call('options_save', data, oldOptions)

    if (reload) {
      location.reload()
    }
  })

  return false
}

module.exports = moduleOptions
