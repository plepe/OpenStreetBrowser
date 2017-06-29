function CategoryBase (id, data) {
  this.id = id
  this.parentCategory = null
  this.data = data
  this.isOpen = false
  this.dom = document.createElement('div')
  this.dom.className = 'category category-' + data.type

  if (this.id !== 'index') {
    var domHeader = document.createElement('header')
    this.dom.appendChild(domHeader)

    var name = ('name:' + ui_lang) in this.data ? this.data['name:en'] : lang('category:' + this.id)

    var a = document.createElement('a')
    a.appendChild(document.createTextNode(name))
    a.href = '#'
    a.onclick = this.toggle.bind(this)
    domHeader.appendChild(a)
  }

  this.domContent = document.createElement('div')
  this.domContent.className = 'content'
  this.dom.appendChild(this.domContent)
}

CategoryBase.prototype.setMap = function (map) {
  this.map = map
}

CategoryBase.prototype.setParent = function (parent) {
  this.parent = parent
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
  if (this.isOpen)
    return

  if (this.parent) {
    this.parent.open()
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
}

CategoryBase.prototype.close = function () {
  if (!this.isOpen)
    return

  this.dom.classList.remove('open')

  this.isOpen = false
}

CategoryBase.prototype.toggle = function () {
  if (this.isOpen) {
    this.close()
  } else {
    this.open()
  }

  return false
}

module.exports = CategoryBase
