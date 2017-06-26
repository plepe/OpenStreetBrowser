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
    this.isOpen = true
    return
  }

  this.childrenCategories = {}

  for (var i = 0; i < this.data.subCategories.length; i++) {
    var data = this.data.subCategories[i]
    var childDom = document.createElement('div')
    childDom.className = 'categoryWrapper'
    this.domContent.appendChild(childDom)
    this.childrenDoms[data.id] = childDom

    this.childrenCategories[data.id] = null

    if ('type' in data) {
      OpenStreetBrowserLoader.getCategoryFromData(data.id, data, this._loadChildCategory.bind(this))
    } else {
      OpenStreetBrowserLoader.getCategory(data.id, this._loadChildCategory.bind(this))
    }
  }
}

CategoryIndex.prototype._loadChildCategory = function (err, category) {
  if (err) {
    return
  }

  this.childrenCategories[category.id] = category

  category.setParent(this)
  category.setParentDom(this.childrenDoms[category.id])
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
