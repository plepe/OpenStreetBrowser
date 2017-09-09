var async = require('async')
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

  this._loadChildrenCategories(function (err) {
    console.log(err)
  })
}

CategoryIndex.prototype.recalc = function () {
  for (var k in this.childrenCategories) {
    if (this.childrenCategories[k]) {
      this.childrenCategories[k].recalc()
    }
  }
}

CategoryIndex.prototype._loadChildrenCategories = function (callback) {
  this.childrenCategories = {}

  async.forEach(this.data.subCategories,
    function (data, callback) {
      var childDom = document.createElement('div')
      childDom.className = 'categoryWrapper'
      this.domContent.appendChild(childDom)
      this.childrenDoms[data.id] = childDom

      this.childrenCategories[data.id] = null

      if ('type' in data) {
        OpenStreetBrowserLoader.getCategoryFromData(data.id, data, this._loadChildCategory.bind(this, callback))
      } else {
        OpenStreetBrowserLoader.getCategory(data.id, this._loadChildCategory.bind(this, callback))
      }
    }.bind(this),
    function (err) {
      if (callback) {
        callback(err)
      }
    }
  )
}

CategoryIndex.prototype._loadChildCategory = function (callback, err, category) {
  if (err) {
    return callback(err)
  }

  this.childrenCategories[category.id] = category

  category.setParent(this)
  category.setParentDom(this.childrenDoms[category.id])

  callback(err, category)
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
