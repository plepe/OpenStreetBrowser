const tabs = require('modulekit-tabs')
const httpGet = require('./httpGet')
const state = require('./state')
require('./nominatim-search.css')

let tab
let customBoundsForm
let customBoundsObjectNames = {}
let customBoundsObjects = {}

function applyCustomForm () {
  let data = customBoundsForm.get_data()
  global.boundingObject.setConfig(data)
  state.update()
  global.boundingObject.emit('update')

  if (data.object === 'viewport') {
    tab.header.classList.remove('active')
  } else {
    tab.header.classList.add('active')
  }
}

function addBoundsObject (id) {
  global.overpassFrontend.get(id,
    {
      properties: OverpassFrontend.ALL
    },
    (err, object) => {
      let name = object.tags.name || object.tags.operator
      customBoundsObjectNames[id] = name
      customBoundsObjects[id] = object

      customBoundsForm.refresh()

      customBoundsForm.set_data({ object: id })
      applyCustomForm()
    },
    (err) => {
      if (err) {
        alert(err)
      }
    }
  )
}

function objectValues () {
  let result = {
    viewport: lang('map section'),
    mouse: lang('mousepointer')
  }

  for (let id in customBoundsObjectNames) {
    result[id] = customBoundsObjectNames[id]
  }

  return result
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
      values_func: { js: objectValues },
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
  }, {
    change_on_input: true
  })

  customBoundsForm.show(domForm)

  customBoundsForm.onchange = applyCustomForm

  tab.on('select', () => {
    customBoundsForm.resize()
  })

  global.boundingObject.setCustomObjects(customBoundsObjects)
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

    if (!(config.object in objectValues())) {
      addBoundsObject(config.object)
    }

    customBoundsForm.set_data(config)
    global.boundingObject.setConfig(config)
  }
})

register_hook('show-popup', (object, category, content, callback) => {
  let footer = content.querySelector('.popup-footer')
  if (footer) {
    let li = document.createElement('li')
    let a = document.createElement('a')
    li.appendChild(a)
    a.innerHTML = lang('bounds:use as boundary')
    a.href = '#'
    a.onclick = () => {
      addBoundsObject(object.id)
      return false
    }
    footer.appendChild(li)
    footer.appendChild
  }

  callback()
})
