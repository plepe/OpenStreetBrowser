const ModalWindow = require('window-modal')
const tabs = require('modulekit-tabs')
const yaml = require('js-yaml')
const md5 = require('md5')

const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

class CustomCategoryRepository {
  constructor () {
  }

  load (callback) {
    callback(null)
  }

  clearCache () {
  }

  getCategory (id, options, callback) {
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

    const input = document.createElement('input')
    input.type = 'submit'
    input.value = lang('apply')
    this.modal.content.element.appendChild(input)

    input.onclick = () => {
      this.applyContent(this.textarea.value)
      ajax('customCategory', { content: this.textarea.value }, (result) => {})
      return true
    }
  }

  applyContent (content) {
    this.content = content

    const id = 'custom/' + md5(content)
    const data = yaml.load(content)

    if (this.category) {
      this.category.remove()
      this.category = null
    }

    OpenStreetBrowserLoader.getCategoryFromData(id, {}, data, (err, category) => {
      if (err) {
        return global.alert(err)
      }

      this.category = category
      this.category.setParentDom(document.getElementById('contentListAddCategories'))
      this.category.setMap(global.map)

      if (this.category.tabEdit) {
        this.category.tools.remove(this.category.tabEdit)
      }

      this.category.tabEdit = new tabs.Tab({
        id: 'edit'
      })
      this.category.tools.add(this.category.tabEdit)
      this.category.tabEdit.header.innerHTML = '<i class="fa fa-pen"></i>'
      this.category.tabEdit.on('select', () => {
        this.category.tabEdit.unselect()
        this.edit()
      })

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
