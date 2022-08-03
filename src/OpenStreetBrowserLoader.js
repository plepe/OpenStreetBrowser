var OverpassLayer = require('overpass-layer')

const Repository = require('./Repository')

class OpenStreetBrowserLoader {
  constructor () {
    this.types = {}
    this.categories = {}
    this.repositories = {}
    this.templates = {}
    this._loadClash = {} // if a category is being loaded multiple times, collect callbacks
  }

  setMap (map) {
    this.map = map
  }

  /**
   * @param string id ID of the category
   * @param [object] options Options.
   * @waram {boolean} [options.force=false] Whether repository should be reload or not.
   * @param function callback Callback which will be called with (err, category)
   */
  getCategory (id, options, callback) {
    if (typeof options === 'function') {
      callback = options
      options = {}
    }

    var ids = this.getFullId(id, options)
    if (ids === null) {
      return callback(new Error('invalid id'), null)
    }

    if (options.force) {
      delete this.categories[ids.fullId]
    }

    if (ids.fullId in this.categories) {
      return callback(null, this.categories[ids.fullId])
    }

    var opt = JSON.parse(JSON.stringify(options))
    opt.categoryId = ids.entityId
    opt.repositoryId = ids.repositoryId

    this.getRepository(ids.repositoryId, opt, (err, repository) => {
      // maybe loaded in the meantime?
      if (ids.fullId in this.categories) {
        return callback(null, this.categories[ids.fullId])
      }

      if (err) {
        return callback(err, null)
      }

      repository.getCategory(ids.entityId, opt, (err, data) => {
        // maybe loaded in the meantime?
        if (ids.fullId in this.categories) {
          return callback(null, this.categories[ids.fullId])
        }

        if (err) { return callback(err) }

        this.getCategoryFromData(ids.id, opt, data, (err, category) =>{
          if (category) {
            category.setMap(this.map)
          }

          callback(err, category)
        })
      })
    })
  }

  /**
   * @param string repo ID of the repository
   * @param [object] options Options.
   * @param {boolean} [options.force=false] Whether repository should be reloaded or not.
   * @param function callback Callback which will be called with (err, repository)
   */
  getRepository (id, options, callback) {
    if (id in this.repositories) {
      const repository = this.repositories[id]

      if (repository.loadCallbacks) {
        return repository.loadCallbacks.push((err) => callback(err, repository))
      }

      if (options.force) {
        repository.clearCache()
        return repository.load((err) => {
          if (err) { return callback(err) }

          options.force = false
          callback(repository.err, repository)
        })
      }

      return callback(repository.err, repository)
    }

    this.repositories[id] = new Repository(id)
    this.repositories[id].load((err) => callback(err, this.repositories[id]))
  }

  /**
   * @param [object] options Options.
   * @param {boolean} [options.force=false] Whether repository should be reloaded or not.
   * @param function callback Callback which will be called with (err, list)
   */
  getRepositoryList (options, callback) {
    if (this.list) {
      return callback(null, this.list)
    }

    if (this.repositoryListCallbacks) {
      return this.repositoryListCallbacks.push(callback)
    }

    this.repositoryListCallbacks = [callback]

    var param = []
    param.push('lang=' + encodeURIComponent(ui_lang))
    param.push(config.categoriesRev)
    param = param.length ? '?' + param.join('&') : ''

    fetch('repo.php' + param)
      .then(res => res.json())
      .then(data => {
        this.list = data

        global.setTimeout(() => {
          const cbs = this.repositoryListCallbacks
          this.repositoryListCallbacks = null
          cbs.forEach(cb => cb(null, this.list))
        }, 0)
      })
      .catch(err => {
        global.setTimeout(() => {
          const cbs = this.repositoryListCallbacks
          this.repositoryListCallbacks = null
          cbs.forEach(cb => cb(err))
        }, 0)
      })
  }

  /**
   * @param string id ID of the template
   * @parapm [object] options Options.
   * @waram {boolean} [options.force=false] Whether repository should be reload or not.
   * @param function callback Callback which will be called with (err, template)
   */
  getTemplate (id, options, callback) {
    if (typeof options === 'function') {
      callback = options
      options = {}
    }

    var ids = this.getFullId(id, options)

    if (options.force) {
      delete this.templates[ids.fullId]
    }

    if (ids.fullId in this.templates) {
      return callback(null, this.templates[ids.fullId])
    }

    var opt = JSON.parse(JSON.stringify(options))
    opt.templateId = ids.entityId
    opt.repositoryId = ids.repositoryId

    this.getRepository(ids.repositoryId, opt, (err, repository) => {
      // maybe loaded in the meantime?
      if (ids.fullId in this.templates) {
        return callback(null, this.templates[ids.fullId])
      }

      if (err) {
        return callback(err, null)
      }

      repository.getTemplate(ids.entityId, opt, (err, data) => {
        // maybe loaded in the meantime?
        if (ids.fullId in this.templates) {
          return callback(null, this.templates[ids.fullId])
        }

        if (err) { return callback(err) }

        this.templates[ids.fullId] = OverpassLayer.twig.twig({ data, autoescape: true })

        callback(null, this.templates[ids.fullId])
      })
    })
  }

  getCategoryFromData (id, options, data, callback) {
    var ids = this.getFullId(id, options)

    if (ids.fullId in this.categories) {
      return callback(null, this.categories[ids.fullId])
    }

    if (!data.type) {
      return callback(new Error('no type defined'), null)
    }

    if (!(data.type in this.types)) {
      return callback(new Error('unknown type'), null)
    }

    let repository = this.repositories[ids.repositoryId]

    var opt = JSON.parse(JSON.stringify(options))
    opt.id = ids.id
    var layer = new this.types[data.type](opt, data, repository)

    layer.setMap(this.map)

    this.categories[ids.fullId] = layer

    if ('load' in layer) {
      layer.load(function (err) {
        callback(err, layer)
      })
    } else {
      callback(null, layer)
    }
  }

  getFullId (id, options) {
    var result = {}

    if (!id) {
      return null
    }

    let m = id.match(/^(.*)\/([^/]*)/)
    if (m) {
      result.id = id
      result.repositoryId = m[1]
      result.entityId = m[2]
    } else if (options.repositoryId && options.repositoryId !== 'default') {
      result.repositoryId = options.repositoryId
      result.entityId = id
      result.id = result.repositoryId + '/' + id
    } else {
      result.id = id
      result.repositoryId = 'default'
      result.entityId = id
    }

    result.sublayerId = null
    m = result.entityId.split(/:/)
    if (m.length > 1) {
      result.sublayerId = m[0]
      result.entityId = m[1]
    }

    result.fullId = result.repositoryId + '/' + (result.sublayerId ? result.sublayerId + ':' : '') + result.entityId

    return result
  }

  forget (id) {
    var ids = this.getFullId(id, options)

    this.categories[ids.fullId].remove()
    delete this.categories[ids.fullId]
  }

  registerType (type, classObject) {
    this.types[type] = classObject
  }
}

OpenStreetBrowserLoader.prototype.registerRepository = function (id, repository) {
  this.repositories[id] = repository
}

module.exports = new OpenStreetBrowserLoader()
