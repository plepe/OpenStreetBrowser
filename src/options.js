/* globals form, ajax, options:true */
module.exports = {
  id: 'options',
  appInit (app) {
  }
}

var moduleOptions = {}
var prevPage
var optionsFormEl

register_hook('init', function () {
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

  call_hooks('options_form', def)

  var optionsForm = new form('options', def)
  prevPage = document.getElementById('content').className
  document.getElementById('content').className = 'options'
  var dom = document.getElementById('contentOptions')
  dom.innerHTML = ''

  let orig_options = {
    debug: false
  }
  call_hooks('options_orig_data', orig_options)
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

  call_hooks('options_open', optionsForm, optionsFormEl)

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

    call_hooks('options_save', data, oldOptions)

    if (reload) {
      location.reload()
    }
  })

  return false
}
