var queryString = require('query-string')

var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

register_hook('state-apply', function (state) {
  if (!('categories' in state)) {
    return
  }

  var list = state.categories.split(',')
  list.forEach(function (id) {
    let param

    let m = id.match(/^([0-9A-Z_-]+)(\[(.*)\])/i)
    if (m) {
      id = m[1]
      param = queryString.parse(m[3])
    }

    OpenStreetBrowserLoader.getCategory(id, function (err, category) {
      if (err) {
        console.log("Can't load category " + id + ': ', err)
        return
      }

      if (category) {
        if (param) {
          category.setParam(param)
        }

        if (!category.parentDom) {
          category.setParentDom(document.getElementById('contentListAddCategories'))
        }
        category.open()

      }
    })
  })
})
