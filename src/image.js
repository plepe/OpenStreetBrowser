var wikidata = require('./wikidata')
var cache = {}

function showImage (url, dom) {
  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="' + url +'"><img src="' + url + '"></a>'

  dom.appendChild(div)
}

function showWikimediaImage (value, dom) {
  var url = 'https://commons.wikimedia.org/w/thumb.php?f=' + encodeURIComponent(value) + '&w=' + 235 //imgSize

  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(value) + '"><img src="' + url + '"/></a>'

  dom.appendChild(div)
}

// feature: { id: 'File:xxx.jpg', type: 'wikimedia|url', url: 'https://...' }
function imageLoadAll(data, featureCallback, finalCallback) {
  var id
  var foundImages = []
  var ret = []
  var r
  var callbackCount = 1

  if (data.id in cache) {
    cache[data.id].forEach(function (d) {
      featureCallback(null, d)
    })
    return finalCallback(null)
  }

  if (data.object.tags.image) {
    img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      id = img.substr(5)
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(39))
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      id = decodeURIComponent(img.substr(40))
      foundImages.push(id)
      r = {
        id: id,
        type: 'wikimedia'
      }
      ret.push(r)
      featureCallback(null, r)
    } else {
      foundImages.push(img)
      r = {
        id: img,
        type: 'url'
      }
      ret.push(r)
      featureCallback(null, r)
    }
  }

  if (data.object.tags.wikidata) {
    wikidata.load(data.object.tags.wikidata, function (err, result) {
      if (result.claims && result.claims.P18) {
        result.claims.P18.forEach(function (d) {
          id = d.mainsnak.datavalue.value

          if (foundImages.indexOf(id) === -1) {
            r = {
              id: id,
              type: 'wikimedia'
            }
            ret.push(r)
            featureCallback(null, r)
            foundImages.push(id)
          }
        })
      }

      checkCallback()
    })

    callbackCount++
  }

  if (data.object.tags.wikimedia_commons) {
    var value = data.object.tags.wikimedia_commons

    if (value.substr(0, 9) === 'Category:') {
      ajax('wikimedia', { page: value }, function (result) {
        if (result.images) {
          result.images.forEach(function (d) {
            if (foundImages.indexOf(d) === -1) {
              foundImages.push(d)
	      r = {
		id: d,
		type: 'wikimedia'
	      }
              ret.push(r)
              featureCallback(null, r)
            }
          })
        }

        checkCallback()
      })

      callbackCount++
    } else if (value.substr(0, 5) === 'File:') {
      var id = value.substr(5)
      if (foundImages.indexOf(id) === -1) {
        foundImages.push(id)
        r = {
          id: id,
          type: 'wikimedia'
        }
        ret.push(r)
        featureCallback(null, r)
      }
    }
  }

  checkCallback()

  function checkCallback () {
    callbackCount--

    if (callbackCount === 0) {
      cache[data.id] = ret
      finalCallback(null)
    }
  }
}

register_hook('show-details', function (data, category, dom, callback) {
  var found = 0
  var div = document.createElement('div')
  div.className = 'images loading'

  dom.appendChild(div)

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  imageLoadAll(data,
    function (err, img) {
      if (found === 0) {
        h = document.createElement('h3')
        h.appendChild(document.createTextNode(lang('images')))
        div.insertBefore(h, div.firstChild)
      }

      found++

      switch (img.type) {
        case 'wikimedia':
          showWikimediaImage(img.id, div)
          break;
        case 'url':
          showImage(img.id, div)
          break;
        default:
      }
    },
    function (err) {
      div.classList.remove('loading')
      callback(err)
    }
  )
})
