const tabs = require('modulekit-tabs')
const OverpassLayer = require('overpass-layer')

const CustomBoundingObject = require('./CustomBoundingObject')
const state = require('./state')

let tab
let customBoundsForm
let customBoundsObjectNames = {}
let customBoundsObjects = {}
let boundingObject = null

function applyCustomForm () {
  let data = customBoundsForm.get_data()
  boundingObject.setConfig(data)
  state.update()
  boundingObject.emit('update')

  if (data.object === 'viewport') {
    tab.header.classList.remove('active')
  } else {
    tab.header.classList.add('active')
  }
}

function refresh () {
  customBoundsForm.refresh()

  let dom = document.getElementById('custom-bounds_object')
  let labels = dom.getElementsByTagName('label')

  for (let i = 0; i < labels.length; i++) {
    let label = labels[i]
    let value = label.getAttribute('for').substr(21)

    if (value === 'viewport' || value === 'mouse') {
      continue
    }

    label.appendChild(document.createTextNode(' '))

    let x = document.createElement('button')
    x.appendChild(document.createTextNode('×'))
    label.appendChild(x)

    x.onclick = () => {
      let data = customBoundsForm.get_data()

      delete customBoundsObjectNames[value]
      delete customBoundsObjects[value]

      refresh()

      if (data.object === value) {
        customBoundsForm.set_data({ object: 'viewport' })
      }
      applyCustomForm()
    }
  }
}

function addBoundsObject (id, callback) {
  tab.select()

  if (!id) {
    return callback()
  }

  global.overpassFrontend.get(id,
    {
      properties: OverpassFrontend.ALL
    },
    (err, object) => {
      if (err) {
        return callback(err)
      }

      let name = object.tags ? object.tags.name || object.tags.operator || object.tags.ref || object.id : object.id
      customBoundsObjectNames[id] = name
      customBoundsObjects[id] = object

      refresh()

      callback()
    },
    (err) => {
      if (err) {
        return callback(err)
      }
    }
  )
}

function addActivateBoundsObject (id) {
  addBoundsObject(id, (err) => {
    if (err) {
      return alert(err)
    }

    customBoundsForm.set_data({ object: id })
    global.customBounds[id] = {}
    applyCustomForm()

    ajax('custom_bounds_add', { id }, () => { console.log('x') })
  })
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

register_hook('categoryOpen', function (category) {
  if (category.layer && category.layer instanceof OverpassLayer) {
    category.layer.setBoundingObject(boundingObject)
  }
})

register_hook('init', function () {
  boundingObject = new CustomBoundingObject(global.map)

  tab = new tabs.Tab({
    id: 'customBounds',
    weight: -1
  })
  tab.content.classList.add('custom-bounds')
  global.tabs.add(tab)

  tab.header.innerHTML = '<i style="position: relative; line-height: 100%;" class="fas fa-square-full"><span style="position: absolute; left: 0; right: 0; height: 100%; margin: auto; color: white; font-size: 60%; text-align: center;"><i class="fas fa-circle"></i></span></i>'
  tab.header.title = lang('bounds:title')

  let h3 = document.createElement('h3')
  h3.appendChild(document.createTextNode(lang('bounds:title')))
  tab.content.appendChild(h3)

  let p = document.createElement('p')
  p.appendChild(document.createTextNode(lang('bounds:info')))
  tab.content.appendChild(p)

  let domForm = document.createElement('form')
  domForm.onsubmit = () => false
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

  boundingObject.setCustomObjects(customBoundsObjects)
})

register_hook('initFinish', function () {
  if (global.customBounds) {
    for (var id in global.customBounds) {
      if (Array.isArray(global.customBounds[id])) {
        global.customBounds[id] = {}
      }

      addBoundsObject(id, () => {})
    }
  }
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
      addActivateBoundsObject(config.object)
    }

    customBoundsForm.set_data(config)
    boundingObject.setConfig(config)
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
      addActivateBoundsObject(object.id)
      map.closePopup()
      return false
    }
    footer.appendChild(li)
    footer.appendChild
  }

  callback()
})
