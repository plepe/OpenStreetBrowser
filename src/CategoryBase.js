/* global lang, ui_lang, options, alert */
/* eslint camelcase: 0 */
var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var tabs = require('modulekit-tabs')
const ee = require('event-emitter')

function CategoryBase (options, data, repository) {
  if (typeof options === 'string') {
    this.id = options
    this.options = {}
  } else {
    this.id = options.id
    this.options = options
  }
  this.repository = repository
  this.parentCategory = null
  this.childrenLoadingCount = 0
  this.data = data
  this.isOpen = false
  this.dom = document.createElement('div')
  this.dom.className = 'category category-' + data.type
  var name
  var a

  this.domHeader = document.createElement('header')
  this.dom.appendChild(this.domHeader)

  if ('name' in this.data) {
    if (typeof this.data.name === 'object') {
      name = lang(this.data.name)
    } else {
      name = this.data.name
    }
  } else if (('name:' + ui_lang) in this.data) {
    name = this.data['name:' + ui_lang]
  } else {
    name = lang('category:' + this.id)
  }

  a = document.createElement('a')
  a.appendChild(document.createTextNode(name))
  a.href = '#'
  a.onclick = this.toggle.bind(this)
  this.domHeader.appendChild(a)

  if (this.options.repositoryId && this.options.repositoryId !== 'default') {
    a = document.createElement('span')
    a.className = 'repoId'
    a.appendChild(document.createTextNode(this.options.repositoryId))
    this.domHeader.appendChild(a)
  }

  if (this.shallShowReload()) {
    a = document.createElement('a')
    a.appendChild(document.createTextNode('⟳'))
    a.title = lang('reload')
    a.className = 'reload'
    a.onclick = function () {
      var id = this.id
      var isOpen = this.isOpen

      this.reload(function (err, category) {
        if (err) {
          alert('Error reloading category ' + id + ': ' + err)
        }

        if (isOpen) {
          category.open()
        }
      })
    }.bind(this)
    this.domHeader.appendChild(a)
  }

  this.tools = new tabs.Tabs(this.dom)
  this.tools.node.classList.add('tools')

  this.domContent = document.createElement('div')
  this.domContent.className = 'content'
  this.dom.appendChild(this.domContent)
}

CategoryBase.prototype.load = function (callback) {
  callback()
}

CategoryBase.prototype.shallShowReload = function () {
  return options.debug
}

CategoryBase.prototype.setMap = function (map) {
  this.map = map
}

CategoryBase.prototype.setParent = function (parent) {
  // if this was a root category, remove from list
  delete(global.rootCategories[this.id])

  this.parentCategory = parent

  if (this.isOpen) {
    this.parentCategory.open()
  }
}

CategoryBase.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
  if (typeof this.parentDom !== 'string') {
    this.parentDom.appendChild(this.dom)

    if (this.isOpen) {
      this.parentDom.parentNode.classList.add('open')
    }
  }
}

CategoryBase.prototype.open = function () {
  if (this.isOpen) {
    return
  }

  if (this.parentCategory) {
    this.parentCategory.open()
  }

  if (typeof this.parentDom === 'string') {
    var d = document.getElementById(this.parentDom)
    if (d) {
      this.parentDom = d
      this.parentDom.appendChild(this.dom)
    }
  }

  this.dom.classList.add('open')

  this.isOpen = true

  call_hooks('categoryOpen', this)
  this.emit('open')
}

CategoryBase.prototype.close = function () {
  if (!this.isOpen) {
    return
  }

  this.dom.classList.remove('open')

  this.isOpen = false

  call_hooks('categoryClose', this)
  this.emit('close')
}

CategoryBase.prototype.toggle = function () {
  if (this.isOpen) {
    this.close()
  } else {
    this.open()
  }

  return false
}

CategoryBase.prototype.reload = function (callback) {
  var parentCategory = this.parentCategory
  var parentDom = this.parentDom

  OpenStreetBrowserLoader.forget(this.id)

  OpenStreetBrowserLoader.getCategory(this.id, { force: true }, function (err, category) {
    if (err) {
      return callback(err)
    }

    category.setParent(parentCategory)
    category.setParentDom(parentDom)

    callback(null, category)
  })
}

CategoryBase.prototype.remove = function () {
  this.close()

  if (this.parentDom) {
    this.parentDom.removeChild(this.dom)
  }
}

CategoryBase.prototype.recalc = function () {
}

CategoryBase.prototype.notifyChildLoadStart = function (category) {
  if (this.childrenLoadingCount === 0 && this.parentCategory) {
    this.parentCategory.notifyChildLoadStart(this)
  } else {
    document.body.classList.add('loading')
  }
  this.childrenLoadingCount++
}

CategoryBase.prototype.notifyChildLoadEnd = function (category) {
  this.childrenLoadingCount--
  if (this.childrenLoadingCount === 0 && this.parentCategory) {
    this.parentCategory.notifyChildLoadEnd(this)
  } else {
    document.body.classList.remove('loading')
  }
}

CategoryBase.prototype.allMapFeatures = function (callback) {
  callback(null, [])
}

ee(CategoryBase.prototype)

module.exports = CategoryBase
