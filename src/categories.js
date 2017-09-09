var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

register_hook('state-apply', function (state) {
  if (!('categories' in state)) {
    return
  }

  var list = state.categories.split(',')
  for (var i = 0; i < list.length; i++) {
    OpenStreetBrowserLoader.getCategory(list[i], function (err, category) {
      if (category) {
        category.open()

        if (!category.parentDom) {
          category.setParentDom(document.getElementById('contentList'))
        }
      }
    })
  }
}.bind(this))
