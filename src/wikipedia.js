function stripLinks (dom) {
  var current = dom.firstChild

  while (current) {
    var next = current.nextSibling

    if (current.tagName === 'A') {
      var c

      while (c = current.firstChild) {
        dom.insertBefore(c, current)
      }

      dom.removeChild(current)
    }

    current = next
  }
}

function prepare (text) {
  var ret = ''

  var div = document.createElement('div')
  div.innerHTML = text

  var contents = div.getElementsByTagName('div')
  for (var i = 0; i < contents.length; i++) {
    if (contents[i].id === 'mw-content-text') {
      var content = contents[i]
      break
    }
  }

  if (!content) {
    return null
  }

  var p = content.getElementsByTagName('p')
  if (!p.length) {
    return null
  }

  p = p[0]
  stripLinks(p)

  return p.innerHTML
}

function get (value, callback) {
  ajax('wikipedia',
    {
      page: value,
      lang: options.data_lang
    },
    function (result) {
      if (!result.content) {
        callback('error', null)
      }

      callback(null, prepare(result.content))
    }
  )
}

register_hook('show-details', function (data, category, dom, callback) {
  var ob = data.object

  if (!('wikipedia' in ob.tags)) {
    return
  }

  var block = document.createElement('div')
  block.className = 'loading'
  dom.appendChild(block)

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  block.appendChild(l)

  var h = document.createElement('h3')
  h.appendChild(document.createTextNode(lang('tag:wikipedia')))
  block.appendChild(h)

  get(ob.tags.wikipedia, function (err, text) {
    if (!text) {
      block.appendChild(document.createTextNode(lang('wikipedia:no-url-parse')))
    }

    var div = document.createElement('div')
    div.innerHTML = text
    block.appendChild(div)

    block.className = ''

    callback(err)
  })
})
