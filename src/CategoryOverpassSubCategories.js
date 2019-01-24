const async = require('async')

const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

class CategoryOverpassSubCategories {
  constructor (master) {
    this.master = master
    this.master.extensions.push(this)
    this.data = this.master.data.featureSubCategories
  }

  load (callback) {
    async.map(this.data,
      (def, done) => OpenStreetBrowserLoader.getCategory(def.id, {}, done),
      (err, result) => {
        this.subCategories = result

        callback(err)
      }
    )
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.featureSubCategories) {
    new CategoryOverpassSubCategories(category)
  }
})
