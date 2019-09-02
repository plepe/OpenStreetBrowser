/* globals form, ajax, options:true */
var moduleOptions = {}
var prevPage

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

  optionsForm.set_data(options)

  var f = document.createElement('form')
  f.onsubmit = moduleOptions.submit.bind(this, optionsForm)
  dom.appendChild(f)

  optionsForm.show(f)

  var input = document.createElement('button')
  input.innerHTML = lang('save')
  f.appendChild(input)

  input = document.createElement('button')
  input.innerHTML = lang('cancel')
  f.appendChild(input)
  input.onclick = function () {
    document.getElementById('content').className = prevPage
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

    document.getElementById('content').className = prevPage

    call_hooks('options_save', data, oldOptions)

    if (reload) {
      location.reload()
    }
  })

  return false
}

module.exports = moduleOptions
