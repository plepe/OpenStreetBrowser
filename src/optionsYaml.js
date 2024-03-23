const yaml = require('js-yaml')
let error = false

function createYaml (form) {
  const data = form.get_data()
  let result = '### OpenStreetBrowser Options ###\n'

  Object.entries(form.def).forEach(([k, d]) => {
    result += '## ' + d.name + '\n'
    if (d.desc) {
      result += '# ' + d.desc.split('\n').join('\n# ') + '\n'
    }

    const o = {}
    o[k] = data[k]
    result += yaml.dump(o) + '\n'
  })

  return result
}

register_hook('options_open', (optionsForm, optionsFormEl) => {
  const inputYaml = document.createElement('textarea')

  const button = document.createElement('button')
  button.innerHTML = lang('YAML')
  optionsFormEl.appendChild(button)
  button.onclick = function () {
    if (optionsForm.table.style.display === 'none') {
      if (!error) {
        optionsForm.table.style.display = 'block'
        inputYaml.style.display = 'none'
      }

      return false
    }

    optionsForm.table.style.display = 'none'
    inputYaml.style.display = 'block'
    inputYaml.value = createYaml(optionsForm)
    inputYaml.style.width = '100%'
    inputYaml.style.height = inputYaml.scrollHeight + 'px'
    return false
  }

  inputYaml.name = 'options-yaml'
  inputYaml.style.display = 'none'
  optionsFormEl.insertBefore(inputYaml, optionsFormEl.firstChild)
  inputYaml.onblur = () => {
    updateForm()
  }

  function updateForm () {
    let options
    try {
      options = yaml.load(inputYaml.value)
      error = false
    } catch (e) {
      global.alert(e.message)
      error = true
    }

    optionsForm.set_data(options)
  }
})
