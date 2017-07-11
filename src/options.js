var moduleOptions = {}

register_hook('init', function () {
  var footer = document.getElementById('footer')

  var link = document.createElement('a')
  link.innerHTML = 'Options'
  link.href = '#options'
  link.onclick = moduleOptions.open

  footer.appendChild(link)
})

moduleOptions.open = function () {
  var def = {}

  call_hooks('options_form', def)

  var options_form = new form('options', def)
  var dom = document.getElementById('content')
  dom.innerHTML = ''

  var f = document.createElement('form')
  f.onsubmit = moduleOptions.submit.bind(this, options_form)
  dom.appendChild(f)

  options_form.show(f)

  var input = document.createElement('button')
  input.innerHTML = 'Save'
  f.appendChild(input)

  return false
}

moduleOptions.submit = function (options_form) {
  var data = options_form.get_data()

  ajax('options_save', null, data, function (ret) {
    console.log(ret)
    call_hooks('options_save', data)

    showRootContent()
  })

  return false
}

module.exports = moduleOptions
