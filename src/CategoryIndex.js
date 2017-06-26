var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
var CategoryBase = require('./CategoryBase')

CategoryIndex.prototype = Object.create(CategoryBase.prototype)
CategoryIndex.prototype.constructor = CategoryIndex
function CategoryIndex (id, data) {
  CategoryBase.call(this, id, data)

  this.childrenDoms = {}
  this.childrenCategories = null
}

CategoryIndex.prototype.open = function () {
  if (this.isOpen)
    return

  CategoryBase.prototype.open.call(this)

  if (this.childrenCategories !== null) {
    this.dom.style.display = 'block'
    this.isOpen = true
    return
  }

  this.childrenCategories = {}

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

        category.setParent(this)

        this.childrenCategories[category.id] = category
      }.bind(this))
    }
  }
}

CategoryIndex.prototype.close = function () {
  if (!this.isOpen)
    return

  CategoryBase.prototype.close.call(this)

  for (var k in this.childrenCategories) {
    if (this.childrenCategories[k]) {
      this.childrenCategories[k].close()
    }
  }
}

CategoryIndex.prototype.toggleCategory = function (id) {
  OpenStreetBrowserLoader.getCategory(id, function (err, category) {
    if (err) {
      alert(err)
      return
    }

    category.setParent(this)
    category.setParentDom(this.childrenDoms[id])
    this.childrenCategories[id] = category

    category.toggle()
  }.bind(this))
}

OpenStreetBrowserLoader.registerType('index', CategoryIndex)
module.exports = CategoryIndex
