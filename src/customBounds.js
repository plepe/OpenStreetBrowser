const tabs = require('modulekit-tabs')
const httpGet = require('./httpGet')
const state = require('./state')
require('./nominatim-search.css')

let tab
let customBoundsForm

function applyCustomForm () {
  let data = customBoundsForm.get_data()
  global.boundingObject.setConfig(data)
  state.update()
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
      name: lang('bounds:object'),
      type: 'radio',
      values: {
        viewport: lang('map section'),
        mouse: lang('mousepointer')
      },
      default: 'viewport'
    },
    buffer: {
      name: lang('bounds:buffer'),
      desc: 'meters',
      type: 'float',
      show_depend: [ 'not', [ 'check', 'object', [ 'is', 'viewport' ] ] ],
      default: '100'
    },
    options: {
      name: lang('main:options'),
      type: 'checkbox',
      show_depend: [ 'not', [ 'check', 'object', [ 'is', 'viewport' ] ] ],
      values: {
        cropView: {
          name: lang('bounds:crop at map section')
        }
      },
      default: [ 'cropView' ]
    }
  })

  customBoundsForm.show(domForm)

  customBoundsForm.onchange = applyCustomForm

  tab.on('select', () => {
    customBoundsForm.resize()
  })
})

register_hook('state-get', state => {
  let config = customBoundsForm.get_data()

  if (config.object === null || config.object === 'viewport') {
    return
  }

  state.bounds =
    config.object + '/' +
    config.buffer + '/' +
    config.options.join(',')
})

register_hook('state-apply', state => {
  if (state.bounds) {
    let config = state.bounds.split('/')
    config = {
      object: config[0],
      buffer: config[1],
      options: config[2].split(',')
    }

    customBoundsForm.set_data(config)
    global.boundingObject.setConfig(config)
  }
})
