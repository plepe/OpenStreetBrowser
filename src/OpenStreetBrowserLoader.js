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

OpenStreetBrowserLoader.prototype.getCategory = function (id, callback) {
  if (id in this.categories) {
    callback(null, this.categories[id])
    return
  }

  var repo = 'default'

  if (repo in this.repoCache) {
    this.getCategoryFromData(id, this.repoCache[repo][id], function (err, category) {
      if (category) {
        category.setMap(this.map)
      }

      callback(err, category)
    })
    return
  }

  if (repo in this._loadClash) {
    this._loadClash[repo].push([ id, callback ])
    return
  }

  this._loadClash[repo] = [ [ id, callback ] ]

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      return callback(req.statusText, null)
    }

    this.repoCache[repo] = JSON.parse(req.responseText)

    var todo = this._loadClash[repo]
    delete this._loadClash[repo]

    todo.forEach(function (c) {
      var id = c[0]
      var callback = c[1]

      if (id in this.categories) {
        callback(null, this.categories[id])
      } else {
        this.getCategoryFromData(id, this.repoCache[repo][id], function (err, category) {
          if (category) {
            category.setMap(this.map)
          }

          callback(err, category)
        })
      }
    }.bind(this))
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', reqListener.bind(this, req))
  req.open('GET', 'repo.php?repo=' + repo + '&' + config.categoriesRev)
  req.send()
}

OpenStreetBrowserLoader.prototype.getTemplate = function (id, callback) {
  if (id in this.templates) {
    callback.apply(this, this.templates[id])
    return
  }

  if (id in this._loadClash) {
    this._loadClash[id].push(callback)
    return
  }

  this._loadClash[id] = []

  function reqListener (req) {
    if (req.status !== 200) {
      console.log(req)
      this.templates[id] = [ req.statusText, null ]
    } else {
      this.templates[id] = [ null, OverpassLayer.twig.twig({ data: req.responseText, autoescape: true }) ]
    }

    callback.apply(this, this.templates[id])

    this._loadClash[id].forEach(function (c) {
      c.apply(this, this.templates[id])
    }.bind(this))
  }

  var req = new XMLHttpRequest()
  req.addEventListener('load', reqListener.bind(this, req))
  req.open('GET', config.categoriesDir + '/' + id + '.html?' + config.categoriesRev)
  req.send()
}

OpenStreetBrowserLoader.prototype.getCategoryFromData = function (id, data, callback) {
  if (id in this.categories) {
    callback(null, this.categories[id])
    return
  }

  if (!data.type) {
    return callback(new Error('no type defined'), null)
  }

  if (!(data.type in this.types)) {
    return callback(new Error('unknown type'), null)
  }

  var layer = new this.types[data.type](id, data)

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
