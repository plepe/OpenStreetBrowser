var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

register_hook('state-apply', function (state) {
  if (!('categories' in state)) {
    return
  }

  var list = state.categories.split(',')
  list.forEach(function (id) {
    OpenStreetBrowserLoader.getCategory(id, function (err, category) {
      if (err) {
        console.log("Can't load category " + id + ': ', err)
        return
      }

      if (category) {
        category.open()

        if (!category.parentDom) {
          category.setParentDom(document.getElementById('contentListAddCategories'))
        }
      }
    })
  })
})
