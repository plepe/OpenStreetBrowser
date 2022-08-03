module.exports = class Repository {
  constructor (id, data) {
    this.id = id
    this.data = data

    this.lang = this.data.lang || {}
  }

  getCategory (id, options, callback) {
    if (!(id in this.data.categories)) {
      return callback(new Error('Repository ' + this.id + ': Category "' + id + '" not defined'), null)
    }

    callback(null, this.data.categories[id])
  }

  getTemplate (id, options, callback) {
    if (!(id in this.data.templates)) {
      return callback(new Error('Repository ' + this.id + ': Template "' + id + '" not defined'), null)
    }

    callback(null, this.data.templates[id])
  }
}
