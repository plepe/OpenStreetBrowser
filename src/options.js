var moduleOptions = {}

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
      'name': 'Debug mode',
      'weight': 10
    }
  }

  call_hooks('options_form', def)

  var options_form = new form('options', def)
  var dom = document.getElementById('content')
  dom.innerHTML = ''

  options_form.set_data(options)

  var f = document.createElement('form')
  f.onsubmit = moduleOptions.submit.bind(this, options_form)
  dom.appendChild(f)

  options_form.show(f)

  var input = document.createElement('button')
  input.innerHTML = lang('save')
  f.appendChild(input)

  return false
}

moduleOptions.submit = function (options_form) {
  var data = options_form.get_data()

  ajax('options_save', null, data, function (ret) {
    call_hooks('options_save', data)

    options = data

    showRootContent()
  })

  return false
}

module.exports = moduleOptions
