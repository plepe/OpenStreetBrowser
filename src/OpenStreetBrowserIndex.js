var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

function OpenStreetBrowserIndex (id, data) {
  this.id = id
  this.data = data
  this.isOpen = false
  this.childrenDoms = {}
  this.childrenCategories = null
  this.dom = document.createElement('div')
}

OpenStreetBrowserIndex.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserIndex.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
  if (typeof this.parentDom !== 'string') {
    this.parentDom.appendChild(this.dom)
  }
}

OpenStreetBrowserIndex.prototype.open = function () {
  if (this.isOpen)
    return

  if (this.childrenCategories !== null) {
    this.dom.style.display = 'block'
    this.isOpen = true
    return
  }

  this.childrenCategories = {}

  if (typeof this.parentDom === 'string') {
    this.parentDom = document.getElementById(this.parentDom)
    this.parentDom.appendChild(this.dom)
  }

  for (var i = 0; i < this.data.subCategories.length; i++) {
    var data = this.data.subCategories[i]
    var dom = document.createElement('div')
    dom.className = 'category'
    this.dom.appendChild(dom)

    var domHeader = document.createElement('header')
    dom.appendChild(domHeader)

    var a = document.createElement('a')
    a.appendChild(document.createTextNode(data['name:en']))
    a.href = '#'
    a.onclick = this.toggleCategory.bind(this, data.id)
    domHeader.appendChild(a)

    var domContent = document.createElement('div')
    this.childrenDoms[data.id] = domContent
    dom.appendChild(domContent)
    this.childrenCategories[data.id] = null

    if ('type' in data) {
      OpenStreetBrowserLoader.getCategoryFromData(data.id, data, function (err, category) {
        if (err) {
          return
        }

        this.childrenCategories[category.id] = category
      }.bind(this))
    }
  }

  this.isOpen = true
}

OpenStreetBrowserIndex.prototype.close = function () {
  if (!this.isOpen)
    return

  for (var k in this.childrenCategories) {
    if (this.childrenCategories[k]) {
      this.childrenCategories[k].close()
    }
  }

  this.dom.style.display = 'none'

  this.isOpen = false
}

OpenStreetBrowserIndex.prototype.toggle = function () {
  if (this.isOpen) {
    this.close()
  } else {
    this.open()
  }
}

OpenStreetBrowserIndex.prototype.toggleCategory = function (id) {
  OpenStreetBrowserLoader.getCategory(id, function (err, category) {
    if (err) {
      alert(err)
      return
    }

    category.setParentDom(this.childrenDoms[id])
    this.childrenCategories[id] = category

    category.toggle()
  }.bind(this))
}

OpenStreetBrowserLoader.registerType('index', OpenStreetBrowserIndex)
module.exports = OpenStreetBrowserIndex
