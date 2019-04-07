const tabs = require('modulekit-tabs')
const httpGet = require('./httpGet')
require('./nominatim-search.css')

let tab
let customBoundsForm

function applyCustomForm () {
  let data = customBoundsForm.get_data()
}

register_hook('init', function () {
  tab = new tabs.Tab({
    id: 'customBounds',
    weight: -1
  })
  tab.content.classList.add('custom-bounds')
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="far fa-square"></i>'
  tab.header.title = lang('custom-bounds')

  let domForm = document.createElement('form')
  tab.content.appendChild(domForm)
  customBoundsForm = new form('custom-bounds', {
    object: {
      name: 'Object',
      type: 'select',
      placeholder: lang('viewport'),
      values: {
        mouse: lang('mousepointer')
      }
    }
  })

  customBoundsForm.show(domForm)

  customBoundsForm.onchange = applyCustomForm

  tab.on('select', () => {
    customBoundsForm.resize()
  })
})
