module.exports = class Repository {
  constructor (id, data) {
    this.id = id
    this.isLoaded = false

    if (data) {
      this.data = data
      this.lang = this.data.lang || {}
      this.loadCallbacks = null
    }
  }

  load (callback) {
    if (this.loadCallbacks) {
      return this.loadCallbacks.push(callback)
    }

    this.loadCallbacks = [callback]

    var param = []

    param.push('repo=' + encodeURIComponent(this.id))
    param.push('lang=' + encodeURIComponent(ui_lang))
    param.push(config.categoriesRev)
    param = param.length ? '?' + param.join('&') : ''

    fetch('repo.php' + param)
      .then(res => res.json())
      .then(data => {
        this.data = data
        this.lang = this.data.lang || {}
        this.err = null

        global.setTimeout(() => {
          const cbs = this.loadCallbacks
          this.loadCallbacks = null
          cbs.forEach(cb => cb(null))
        }, 0)
      })
      .catch(err => {
        this.err = err
        global.setTimeout(() => {
          const cbs = this.loadCallbacks
          this.loadCallbacks = null
          cbs.forEach(cb => cb(err))
        }, 0)
      })
  }

  clearCache () {
    this.data = null
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
