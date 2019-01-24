const async = require('async')

const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

class CategoryOverpassSubCategories {
  constructor (master) {
    this.master = master
    this.master.extensions.push(this)
    this.data = this.master.data.featureSubCategories

    this.master.on('popupOpen', this.popupOpen.bind(this))
    this.master.on('popupUpdate', this.popupUpdate.bind(this))
    this.master.on('popupClose', this.popupClose.bind(this))
  }

  load (callback) {
    async.map(this.data,
      (def, done) => OpenStreetBrowserLoader.getCategory(def.id, { unique: true }, done),
      (err, result) => {
        this.subCategories = result

        callback(err)
      }
    )
  }

  popupOpen (object) {
  }

  popupUpdate (object, dom) {
    this.subCategories.forEach(
      cat => {
        cat.setParentDom(dom)
        cat.open()
      }
    )
  }

  popupClose () {
    this.subCategories.forEach(cat => cat.close())
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.featureSubCategories) {
    new CategoryOverpassSubCategories(category)
  }
})
