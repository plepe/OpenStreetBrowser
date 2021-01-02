var wikidata = require('./wikidata')
const displayBlock = require('./displayBlock')

var cache = {}
var loadClash = {}

function stripLinks (dom) {
  var as = dom.getElementsByTagName('a')
  as = Array.prototype.slice.call(as)

  as.forEach(function (current) {
    while (current.firstChild) {
      current.parentNode.insertBefore(current.firstChild, current)
    }

    current.parentNode.removeChild(current)
  })
}

function prepare (div) {
  var i

  var contents = div.getElementsByTagName('div')
  for (i = 0; i < contents.length; i++) {
    if (contents[i].id === 'mw-content-text') {
      var content = contents[i]
      break
    }
  }

  if (!content) {
    return null
  }

  var p = content.firstChild.firstChild
  while (p && (p.tagName !== 'P' || p.className !== '' || p.textContent.match(/^\s*$/))) {
    p = p.nextSibling
  }

  if (!p) {
    return null
  }

  stripLinks(p)

  // first image
  var imgs = content.getElementsByTagName('img')
  for (i = 0; i < imgs.length; i++) {
    var img = imgs[i]

    // ignore icons
    if (img.width <= 64 && img.height <= 64) {
      continue
    }

    img.removeAttribute('width')
    img.removeAttribute('height')
    p.insertBefore(img, p.firstChild)

    break
  }

  return p.innerHTML
}

function get (value, callback) {
  var cacheId = options.data_lang + ':' + value
  if (cacheId in cache) {
    return callback(null, cache[cacheId])
  }

  if (cacheId in loadClash) {
    loadClash[cacheId].push(callback)
    return
  }
  loadClash[cacheId] = []

  ajax('wikipedia',
    {
      page: value,
      lang: options.data_lang
    },
    function (result) {
      if (!result.content) {
        return callback(new Error('error'), null)
      }

      cache[cacheId] = result

      callback(null, result)

      loadClash[cacheId].forEach(function (d) {
        d(null, result)
      })
      delete loadClash[cacheId]
    }
  )
}

function getAbstract (value, callback) {
  get(value,
    function (err, result) {
      var text = null

      if (result) {
        var div = document.createElement('div')
        div.innerHTML = result.content

        text = prepare(div)
        text += ' <a target="_blank" href="' + result.languages[result.language] + '">' + lang('more') + '</a>'
      }

      callback(err, text)
    }
  )
}

register_hook('show-details', function (data, category, dom, callback) {
  var ob = data.object
  var found = 0
  var foundPrefixes = []
  var finished = 0
  var errs = []
  var h, k, m
  var div = document.createElement('div')
  div.className = 'wikipedia'

  if ('wikipedia' in ob.tags) {
    foundPrefixes.push('')

    ob.tags.wikipedia.split(/;/g).forEach(value => {
      value = value.trim()

      found++
      showWikipedia(value, div, done)
    })
  }

  for (k in ob.tags) {
    m = k.match(/^(.*):wikipedia$/)
    if (m) {
      let prefix = m[1]

      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + prefix)))
      div.appendChild(h)

      foundPrefixes.push(prefix)

      ob.tags[k].split(/;/g).forEach(value => {
        value = value.trim()

        found++
        showWikipedia(value, div, done)
      })
    }

    m = k.match(/^((.*):)?wikipedia:(.*)$/)
    if (m) {
      let prefix = m[1]

      if (typeof prefix === 'undefined' && foundPrefixes.indexOf('') !== -1) {
        continue
      }
      if (foundPrefixes.indexOf(prefix) !== -1) {
        continue
      }

      if (prefix) {
        h = document.createElement('h4')
        h.appendChild(document.createTextNode(lang('tag:' + prefix)))
        div.appendChild(h)
      }

      foundPrefixes.push(prefix)

      ;(m[3] + ':' + ob.tags[k]).split(/;/g).forEach(value => {
        found++
        showWikipedia(value, div, done)
      })
    }
  }

  if (ob.tags.wikidata && foundPrefixes.indexOf('') === -1) {
    foundPrefixes.push('')

    ob.tags.wikidata.split(/;/g).forEach(value => {
      value = value.trim()

      found++

      wikidata.load(value, function (err, result) {
        var x

        if (err) {
          return done(err)
        }

        if (!result.sitelinks) {
          return done(new Error('No Wikipedia links defined for Wikidata'))
        }

        if (options.data_lang + 'wiki' in result.sitelinks) {
          x = result.sitelinks[options.data_lang + 'wiki']
          return showWikipedia(options.data_lang + ':' + x.title, div, done)
        }

        for (k in result.sitelinks) {
          if (k === 'commonswiki') {
            continue
          }

          x = result.sitelinks[k]
          m = k.match(/^(.*)wiki$/)
          return showWikipedia(m[1] + ':' + x.title, div, done)
        }

        done()
      })
    })
  }

  for (k in ob.tags) {
    m = k.match(/^(.*):wikidata$/)
    if (m) {
      let prefix = m[1]
      found++
      if (foundPrefixes.indexOf(prefix) !== -1) {
        continue
      }
      foundPrefixes.push(prefix)

      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + prefix)))
      div.appendChild(h)

      ob.tags[k].split(/;/g).forEach(value => {
        value = value.trim()

        wikidata.load(value, (err, result) => {
          var x

          if (err) {
            return done(err)
          }

          if (!result.sitelinks) {
            return done()
          }

          if (options.data_lang + 'wiki' in result.sitelinks) {
            x = result.sitelinks[options.data_lang + 'wiki']
            return showWikipedia(options.data_lang + ':' + x.title, div, done)
          }

          for (k in result.sitelinks) {
            if (k === 'commonswiki') {
              continue
            }

            x = result.sitelinks[k]
            m = k.match(/^(.*)wiki$/)
            return showWikipedia(m[1] + ':' + x.title, div, done)
          }

          done()
        })
      })
    }
  }

  if (found) {
    displayBlock({
      dom,
      title: lang('tag:wikipedia'),
      content: div,
      order: 1
    })
  }

  function done (err) {
    finished++

    if (err) {
      errs.push(err)
    }

    if (found === finished) {
      callback(errs.length ? errs : null)
    }
  }
})

function showWikipedia (tagValue, dom, callback) {
  var block = document.createElement('div')
  block.className = 'loading'
  dom.appendChild(block)

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  block.appendChild(l)

  getAbstract(tagValue, function (err, text) {
    if (!text) {
      block.appendChild(document.createTextNode(lang('wikipedia:no-url-parse')))
    }

    var div = document.createElement('div')
    div.innerHTML = text
    block.appendChild(div)

    block.removeChild(l)
    block.className = 'clearfix'

    callback(err)
  })
}

function getImages (tagValue, callback) {
  var i

  get(tagValue, function (err, result) {
    if (err) {
      return callback(err, null)
    }

    var div = document.createElement('div')
    div.innerHTML = result.content

    var imgs = div.getElementsByTagName('img')
    var ret = []

    for (i = 0; i < imgs.length; i++) {
      var img = imgs[i]

      // ignore icons
      if (img.width <= 64 && img.height <= 64) {
        continue
      }

      img.removeAttribute('width')
      img.removeAttribute('height')

      var m = img.src.match(/^https?:\/\/upload.wikimedia.org\/wikipedia\/commons\/thumb\/\w+\/\w+\/([^/]+)/)
      if (m) {
        var file = decodeURIComponent(m[1]).replace(/_/g, ' ')
        ret.push({
          id: file,
          width: img.getAttribute('data-file-width'),
          height: img.getAttribute('data-file-height')
        })
      }
    }

    callback(null, ret)
  })
}

module.exports = {
  getImages: getImages
}
