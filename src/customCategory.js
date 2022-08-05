const ModalWindow = require('window-modal')
const tabs = require('modulekit-tabs')
const yaml = require('js-yaml')
const md5 = require('md5')

const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

const cache = {}
const customCategories = []

class CustomCategoryRepository {
  constructor () {
  }

  load (callback) {
    callback(null)
  }

  clearCache () {
  }

  getCategory (id, options, callback) {
    if (id in cache) {
      return callback(null, yaml.load(cache[id]))
    }

    ajax('customCategory', { id }, (result) => {
      callback(null, yaml.load(result))
    })
  }

  getTemplate (id, options, callback) {
    callback(null, '')
  }
}

class CustomCategory {
  constructor () {
    customCategories.push(this)
  }

  load (id, callback) {
    this.id = id
    ajax('customCategory', { id }, (result) => {
      this.content = result
      callback(null, result)
    })
  }

  edit () {
    if (this.modal) {
      this.modal.focused = true
      return
    }

    this.modal = new ModalWindow({
      title: 'Custom Category',
      hideMinimize: true,
      zIndex: '99999'
    })

    this.modal.addEventListener('close', () => {
      this.modal = null
    })

    this.textarea = document.createElement('textarea')
    this.modal.content.element.appendChild(this.textarea)
    if (this.content !== undefined) {
      this.textarea.value = this.content
    }

    const controls = document.createElement('div')
    controls.className = 'controls'
    this.modal.content.element.appendChild(controls)

    const input = document.createElement('input')
    input.type = 'submit'
    input.value = lang('apply')
    controls.appendChild(input)

    input.onclick = () => {
      try {
        yaml.load(this.textarea.value)
      }
      catch (e) {
        return global.alert(e)
      }

      this.applyContent(this.textarea.value)
      ajax('customCategory', { content: this.textarea.value }, (result) => {})
      return true
    }
  }

  applyContent (content) {
    this.content = content

    const id = md5(content)
    this.id = id
    cache[id] = content

    if (this.category) {
      this.category.remove()
      this.category = null
    }

    OpenStreetBrowserLoader.getCategory('custom/' + id, {}, (err, category) => {
      if (err) {
        return global.alert(err)
      }

      this.category = category
      this.category.setParentDom(document.getElementById('contentListAddCategories'))

      this.category.open()
    })
  }
}


function createCustomCategory () {
  let category

  category = new CustomCategory()
  category.edit()

  return false
}

function editCustomCategory (id, category) {
  let done = customCategories.filter(customCategory => {
    if (customCategory.id === id) {
      customCategory.edit()
      return true
    }
  })

  if (!done.length) {
    const customCategory = new CustomCategory()
    customCategory.load(id, (err) => {
      if (err) { return global.alert(err) }
      customCategory.category = category
      customCategory.edit()
    })
  }
}

module.exports = function customCategory (content) {
  let div = document.createElement('div')

  let a = document.createElement('a')
  a.innerHTML = lang('customCategory:create')
  a.href = '#'
  a.onclick = createCustomCategory
  div.appendChild(a)
  content.appendChild(div)
}

hooks.register('init', () => {
  OpenStreetBrowserLoader.registerRepository('custom', new CustomCategoryRepository())
})
register_hook('category-overpass-init', (category) => {
  const m = category.id.match(/^custom\/(.*)$/)
  if (m) {
    const id = m[1]

    if (category.tabEdit) {
      category.tools.remove(this.category.tabEdit)
    }

    category.tabEdit = new tabs.Tab({
      id: 'edit',
      weight: 9
    })
    category.tools.add(category.tabEdit)
    category.tabEdit.header.innerHTML = '<i class="fa fa-pen"></i>'
    category.tabEdit.on('select', () => {
      category.tabEdit.unselect()
      editCustomCategory(id, category)
    })
  }
})
