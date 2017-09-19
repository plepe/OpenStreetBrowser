var wikidata = require('./wikidata')

var cache = {}
var loadClash = {}

function stripLinks (dom) {
  var as = dom.getElementsByTagName('a')
  var as = Array.prototype.slice.call(as)

  as.forEach(function (current) {
    var c

    while (c = current.firstChild) {
      current.parentNode.insertBefore(c, current)
    }

    current.parentNode.removeChild(current)
  })
}

function prepare (text) {
  var ret = ''
  var i

  var div = document.createElement('div')
  div.innerHTML = text

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
  while (p && p.tagName !== 'P') {
    p = p.nextSibling
  }

  if (!p) {
    return null
  }

  stripLinks(p)

  // first image
  var imgs = div.getElementsByTagName('img')
  for (i = 0; i < imgs.length; i++) {
    var img = imgs[i]

    // ignore icons
    if (img.width <= 64 && img.height <= 64) {
      continue
    }

    img.removeAttribute('width')
    img.removeAttribute('height')
    p.insertBefore(img, p.firstChild)

    break;
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
        return callback('error', null)
      }

      var text = prepare(result.content)
      text += ' <a target="_blank" href="' + result.languages[result.language] + '">' + lang('more') + '</a>'

      cache[cacheId] = text

      callback(null, text)

      loadClash[cacheId].forEach(function (d) {
        d(null, text)
      })
      delete loadClash[cacheId]
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
    found++
    foundPrefixes.push('')

    showWikipedia(ob.tags.wikipedia, div, done)
  }

  for (k in ob.tags) {
    if (m = k.match(/^(.*):wikipedia$/)) {
      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + m[1])))
      div.appendChild(h)

      found++
      foundPrefixes.push(m[1])
      showWikipedia(ob.tags[k], div, done)
    }

    if (m = k.match(/^((.*):)?wikipedia:(.*)$/)) {
      if (typeof m[1] === 'undefined' && foundPrefixes.indexOf('') !== -1) {
        continue
      }
      if (foundPrefixes.indexOf(m[1]) !== -1) {
        continue
      }

      if (m[1]) {
        h = document.createElement('h4')
        h.appendChild(document.createTextNode(lang('tag:' + m[1])))
        div.appendChild(h)
      }

      found++
      foundPrefixes.push(m[1])
      showWikipedia(m[3] + ':' + ob.tags[k], div, done)
    }
  }

  if (ob.tags.wikidata && foundPrefixes.indexOf('') === -1) {
    found++
    foundPrefixes.push('')

    wikidata.load(ob.tags[k], function (err, result) {
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
  }

  for (k in ob.tags) {
    if (m = k.match(/^(.*):wikidata$/)) {
      found ++
      if (foundPrefixes.indexOf(m[1]) !== -1) {
        continue
      }
      foundPrefixes.push(m[1])

      wikidata.load(ob.tags[k], function (prefix, err, result) {
        var x

        if (err) {
          return done(err)
        }

        if (!result.sitelinks) {
          return done()
        }

        h = document.createElement('h4')
        h.appendChild(document.createTextNode(lang('tag:' + prefix)))
        div.appendChild(h)

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
      }.bind(this, m[1]))
    }
  }

  if (found) {
    h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('tag:wikipedia')))
    dom.appendChild(h)

    dom.appendChild(div)
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

  get(tagValue, function (err, text) {
    if (!text) {
      block.appendChild(document.createTextNode(lang('wikipedia:no-url-parse')))
    }

    var div = document.createElement('div')
    div.innerHTML = text
    block.appendChild(div)

    block.className = ''

    callback(err)
  })
}
