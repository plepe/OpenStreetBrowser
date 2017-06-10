function OpenStreetBrowserIndex (data) {
  this.data = data
}

OpenStreetBrowserIndex.prototype.addTo = function (map, parentDom) {
  for (var i = 0; i < this.data.subCategories.length; i++) {
    var d = this.data.subCategories[i]
    var dom = document.createElement('div')
    dom.id = 'category-' + d.id
    parentDom.appendChild(dom)

    var domHeader = document.createElement('header')
    dom.appendChild(domHeader)

    var a = document.createElement('a')
    a.appendChild(document.createTextNode(d['name:en']))
    a.href = 'javascript:toggleCategory(' + JSON.stringify(d.id) + ')'
    domHeader.appendChild(a)

    var domContent = document.createElement('div')
    parentDom.appendChild(domContent)
  }
}

module.exports = OpenStreetBrowserIndex
