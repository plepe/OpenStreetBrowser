var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

function OpenStreetBrowserIndex (id, data) {
  this.id = id
  this.data = data
  this.isOpen = false
}

OpenStreetBrowserIndex.prototype.setMap = function (map) {
  this.map = map
}

OpenStreetBrowserIndex.prototype.setParentDom = function (parentDom) {
  this.parentDom = parentDom
}

OpenStreetBrowserIndex.prototype.open = function () {
  if (typeof this.parentDom === 'string') {
    this.parentDom = document.getElementById(this.parentDom)
  }

  for (var i = 0; i < this.data.subCategories.length; i++) {
    var data = this.data.subCategories[i]
    var dom = document.createElement('div')
    dom.id = 'category-' + data.id
    this.parentDom.appendChild(dom)

    var domHeader = document.createElement('header')
    dom.appendChild(domHeader)

    var a = document.createElement('a')
    a.appendChild(document.createTextNode(data['name:en']))
    a.href = 'javascript:toggleCategory(' + JSON.stringify(data.id) + ')'
    domHeader.appendChild(a)

    var domContent = document.createElement('div')
    this.parentDom.appendChild(domContent)
  }
}

OpenStreetBrowserLoader.registerType('index', OpenStreetBrowserIndex)
module.exports = OpenStreetBrowserIndex
