function OpenStreetBrowserIndex (id, data) {
  this.id = id
  this.data = data
}

OpenStreetBrowserIndex.prototype.addTo = function (map, parentDom) {
  for (var i = 0; i < this.data.subCategories.length; i++) {
    var data = this.data.subCategories[i]
    var dom = document.createElement('div')
    dom.id = 'category-' + data.id
    parentDom.appendChild(dom)

    var domHeader = document.createElement('header')
    dom.appendChild(domHeader)

    var a = document.createElement('a')
    a.appendChild(document.createTextNode(data['name:en']))
    a.href = 'javascript:toggleCategory(' + JSON.stringify(data.id) + ')'
    domHeader.appendChild(a)

    var domContent = document.createElement('div')
    parentDom.appendChild(domContent)
  }
}

module.exports = OpenStreetBrowserIndex
