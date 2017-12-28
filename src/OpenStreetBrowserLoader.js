var OverpassLayer = require('overpass-layer')
var jsonMultilineStrings = require('json-multiline-strings')

function OpenStreetBrowserLoader () {
  this.types = {}
  this.categories = {}
  this.repoCache = {}
  this.templates = {}
  this._loadClash = {} // if a category is being loaded multiple times, collect callbacks
}

OpenStreetBrowserLoader.prototype.setMap = function (map) {
  this.map = map
}

/**
 * @param string id ID of the category
 * @parapm [object] options Options.
 * @param function callback Callback which will be called with (err, category)
 */
OpenStreetBrowserLoader.prototype.getCategory = function (id, options, callback) {
  if (typeof options === 'function') {
    callback = options
    options = {}
  }

  var repo
  var categoryId
  var m
  if (m = id.match(/^(.*)\/([^\/]*)/)) {
    repo = m[1]
    categoryId = m[2]
  } else if (options.repositoryId && options.repositoryId !== 'default') {
    repo = options.repositoryId
    categoryId = id
    id = repo + '/' + id
  } else {
    repo = 'default'
    categoryId = id
  }
  var fullId = repo + '/' + categoryId

  if (fullId in this.categories) {
    return callback(null, this.categories[fullId])
  }

  var opt = JSON.parse(JSON.stringify(options))
  opt.categoryId = categoryId
  opt.repositoryId = repo

  this.getRepo(repo, opt, function (err, repoData) {
    // maybe loaded in the meantime?
    if (fullId in this.categories) {
      return callback(null, this.categories[fullId])
    }

    if (err) {
      return callback(err, null)
    }

    if (!(categoryId in repoData.categories)) {
      return callback(new Error('category not defined'), null)
    }

    this.getCategoryFromData(id, opt, repoData.categories[categoryId], function (err, category) {
      if (category) {
        category.setMap(this.map)
      }

      callback(err, category)
    }.bind(this))
  }.bind(this))
}

/**
 * @param string repo ID of the repository
 * @parapm [object] options Options.
 * @param function callback Callback which will be called with (err, repoData)
 */
OpenStreetBrowserLoader.prototype.getRepo = function (repo, options, callback) {
  if (repo in this.repoCache) {
    return callback(null, this.repoCache[repo])
  }

  if (repo in this._loadClash) {
    this._loadClash[repo].push(callback)
    return
  }

  this._loadClash[repo] = [ callback ]

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      return callback(req.statusText, null)
    }

    this.repoCache[repo] = JSON.parse(req.responseText)

    var todo = this._loadClash[repo]
    delete this._loadClash[repo]

    todo.forEach(function (callback) {
      callback(null, this.repoCache[repo])
    }.bind(this))
  }

  var param = []
  if (repo) {
    param.push('repo=' + encodeURIComponent(repo))
  }
  param.push(config.categoriesRev)
  param = param.length ? '?' + param.join('&') : ''

  var req = new XMLHttpRequest()
  req.addEventListener('load', reqListener.bind(this, req))
  req.open('GET', 'repo.php' + param)
  req.send()
}

/**
 * @param string id ID of the template
 * @parapm [object] options Options.
 * @param function callback Callback which will be called with (err, template)
 */
OpenStreetBrowserLoader.prototype.getTemplate = function (id, options, callback) {
  if (typeof options === 'function') {
    callback = options
    options = {}
  }

  var repo
  var templateId
  var m
  if (m = id.match(/^(.*)\/([^\/]*)/)) {
    repo = m[1]
    templateId = m[2]
  } else if (options.repositoryId && options.repositoryId !== 'default') {
    repo = options.repositoryId
    templateId = id
    id = repo + '/' + id
  } else {
    repo = options.repositoryId || 'default'
    templateId = id
  }
  var fullId = repo + '/' + templateId

  if (fullId in this.templates) {
    return callback(null, this.templates[fullId])
  }

  var opt = JSON.parse(JSON.stringify(options))
  opt.templateId = templateId
  opt.repositoryId = repo

  this.getRepo(repo, opt, function (err, repoData) {
    // maybe loaded in the meantime?
    if (fullId in this.templates) {
      return callback(null, this.templates[fullId])
    }

    if (err) {
      return callback(err, null)
    }

    if (!repoData.templates || !(templateId in repoData.templates)) {
      return callback(new Error('template not defined'), null)
    }

    this.templates[id] = OverpassLayer.twig.twig({ data: repoData.templates[templateId], autoescape: true })

    callback(null, this.templates[id])
  }.bind(this))
}

OpenStreetBrowserLoader.prototype.getCategoryFromData = function (id, options, data, callback) {
  var repo
  var categoryId
  var m
  if (m = id.match(/^(.*)\/([^\/]*)/)) {
    repo = m[1]
    categoryId = m[2]
  } else if (options.repositoryId && options.repositoryId !== 'default') {
    repo = options.repositoryId
    categoryId = id
    id = repo + '/' + id
  } else {
    repo = 'default'
    categoryId = id
  }
  var fullId = repo + '/' + categoryId

  if (fullId in this.categories) {
    return callback(null, this.categories[fullId])
  }

  if (!data.type) {
    return callback(new Error('no type defined'), null)
  }

  if (!(data.type in this.types)) {
    return callback(new Error('unknown type'), null)
  }

  var opt = JSON.parse(JSON.stringify(options))
  opt.id = id
  var layer = new this.types[data.type](opt, data)

  layer.setMap(this.map)

  this.categories[id] = layer

  if ('load' in layer) {
    layer.load(function (err) {
      callback(err, layer)
    })
  } else {
    callback(null, layer)
  }
}

OpenStreetBrowserLoader.prototype.forget = function (id) {
  this.categories[id].remove()
  delete this.categories[id]
}

OpenStreetBrowserLoader.prototype.registerType = function (type, classObject) {
  this.types[type] = classObject
}

module.exports = new OpenStreetBrowserLoader()
