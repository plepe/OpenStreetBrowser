var cache = {}

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
    }
  )
}

register_hook('show-details', function (data, category, dom, callback) {
  var ob = data.object
  var found = 0
  var finished = 0
  var errs = []
  var h
  var div = document.createElement('div')
  div.className = 'wikipedia'

  if ('wikipedia' in ob.tags) {
    found++
    showWikipedia(ob.tags.wikipedia, div, done)
  }

  for (var k in ob.tags) {
    var m
    if (m = k.match(/^(.*):wikipedia$/)) {
      h = document.createElement('h4')
      h.appendChild(document.createTextNode(lang('tag:' + m[1])))
      div.appendChild(h)

      found++
      showWikipedia(ob.tags[k], div, done)
    }

    if (m = k.match(/^((.*):)?wikipedia:(.*)$/)) {
      if (m[1]) {
        h = document.createElement('h4')
        h.appendChild(document.createTextNode(lang('tag:' + m[1])))
        div.appendChild(h)
      }

      found++
      showWikipedia(m[3] + ':' + ob.tags[k], div, done)
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
