const async = require('async')
const OverpassLayer = require('overpass-layer')

var wikidata = require('./wikidata')
const displayBlock = require('./displayBlock')

var cache = {}
var getAbstractCache = {}
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
  while (p && (p.tagName !== 'P' || p.className !== '' || p.textContent.match(/^\s*$/) || p.querySelector('span#coordinates'))) {
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
  const cacheId = options.data_lang + ':' + value
  if (cacheId in getAbstractCache) {
    callback(null, getAbstractCache[cacheId])
    return getAbstractCache[cacheId]
  }

  get(value,
    function (err, result) {
      var text = null

      if (result) {
        var div = document.createElement('div')
        div.innerHTML = result.content

        text = prepare(div)
        text += ' <a target="_blank" href="' + result.languages[result.language] + '">' + lang('more') + '</a>'
      }

      getAbstractCache[cacheId] = text

      callback(err, text)
    }
  )
}

function updateDomWikipedia (dom, callback) {
  const wikipediaQueries = dom.querySelectorAll('.wikipedia')
  async.each(
    wikipediaQueries,
    (div, done) => {
      if (div.hasAttribute('data-done')) {
        return done()
      }

      getAbstract(div.getAttribute('data-id'),
        (err, result) => {
          if (result) {
            div.innerHTML = result
            div.setAttribute('data-done', 'true')
          }
          done()
        }
      )
    },
    () => {
      callback()
    }
  )
}

register_hook('show-popup', function (data, category, dom, callback) {
  updateDomWikipedia(dom, () => updateDomWikipedia(dom, () => {}))
  callback()
})

register_hook('show-details', function (data, category, dom, callback) {
  var ob = data.object
  var found = 0
  var foundPrefixes = []
  var finished = 0
  var errs = []
  var h, k, m
  var div = document.createElement('div')
  div.className = 'wikipedia'

  const mainBlock = document.createElement('div')
  div.appendChild(mainBlock)

  if ('wikipedia' in ob.tags) {
    foundPrefixes.push('')

    ob.tags.wikipedia.split(/;/g).forEach(value => {
      value = value.trim()

      found++
      showWikipedia(value, mainBlock, done)
    })
  }

  for (k in ob.tags) {
    m = k.match(/^(.*):wikipedia$/)
    if (m) {
      let prefix = m[1]

      const block = document.createElement('div')
      div.appendChild(block)

      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + prefix)))
      block.appendChild(h)

      foundPrefixes.push(prefix)

      ob.tags[k].split(/;/g).forEach(value => {
        value = value.trim()

        found++
        showWikipedia(value, block, done)
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

      let block = mainBlock
      if (prefix) {
        block = document.createElement('div')
        div.appendChild(block)

        h = document.createElement('h4')
        h.appendChild(document.createTextNode(lang('tag:' + prefix)))
        block.appendChild(h)
      }

      foundPrefixes.push(prefix)

      ;(m[3] + ':' + ob.tags[k]).split(/;/g).forEach(value => {
        found++
        showWikipedia(value, block, done)
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
          return showWikipedia(options.data_lang + ':' + x.title, mainBlock, done)
        }

        for (k in result.sitelinks) {
          if (k === 'commonswiki') {
            continue
          }

          x = result.sitelinks[k]
          m = k.match(/^(.*)wiki$/)
          return showWikipedia(m[1] + ':' + x.title, mainBlock, done)
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

      const block = document.createElement('div')
      div.appendChild(block)

      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + prefix)))
      block.appendChild(h)

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
            return showWikipedia(options.data_lang + ':' + x.title, block, done)
          }

          for (k in result.sitelinks) {
            if (k === 'commonswiki') {
              continue
            }

            x = result.sitelinks[k]
            m = k.match(/^(.*)wiki$/)
            return showWikipedia(m[1] + ':' + x.title, block, done)
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
  } else {
    callback()
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

OverpassLayer.twig.extendFilter('wikipediaAbstract', function (value, param) {
  let result
  const cacheId = options.data_lang + ':' + value
  if (cacheId in getAbstractCache) {
    const text = getAbstractCache[cacheId]
    result = '<div class="wikipedia" data-id="' + value + '" data-done="true">' + text + '</div>'
  } else {
    result = '<div class="wikipedia" data-id="' + value + '"><a href="https://wikidata.org/wiki/' + value + '">' + value + '</a></div>'
  }

  return OverpassLayer.twig.filters.raw(result)
})
