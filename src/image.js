var wikidata = require('./wikidata')

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

register_hook('show-details', function (data, category, dom, callback) {
  var found = 0
  var img
  var foundImages = []
  var div = document.createElement('div')
  div.className = 'images loading'
  var callbackCount = 1

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  if (data.object.tags.image) {
    img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      showWikimediaImage(img.substr(5), div)
      foundImages.push(img.substr(5))
      found++
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      showWikimediaImage(decodeURIComponent(img.substr(39)), div)
      foundImages.push(img.substr(39))
      found++
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      showWikimediaImage(decodeURIComponent(img.substr(40)), div)
      foundImages.push(img.substr(40))
      found++
    } else {
      foundImages.push(img)
      showImage(img, div)
      found++
    }
  }

  if (data.object.tags.wikidata) {
    found++

    wikidata.load(data.object.tags.wikidata, function (err, result) {
      if (result.claims && result.claims.P18) {
        result.claims.P18.forEach(function (d) {
          img = d.mainsnak.datavalue.value

          if (foundImages.indexOf(img) === -1) {
            showWikimediaImage(img, div)
            foundImages.push(img)
          }
        })
      }

      checkCallback()
    })

    callbackCount++
  }

  if (!data.object.tags.wikidata && data.object.tags.wikimedia_commons) {
    var value = data.object.tags.wikimedia_commons

    if (value.substr(0, 9) === 'Category:') {
      found++
      ajax('wikimedia', { page: value }, function (result) {
        if (result.images) {
          result.images.forEach(function (d) {
            if (foundImages.indexOf(d) === -1) {
              showWikimediaImage(d, div)
              foundImages.push(d)
            }
          })
        }

        checkCallback()
      })

      callbackCount++
    } else if (value.substr(0, 5) === 'File:') {
      found++
      if (foundImages.indexOf(value.substr(5)) === -1) {
        showWikimediaImage(value.substr(5), div)
        foundImages.push(d)
      }
    }
  }

  if (found) {
    h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('images')))
    dom.appendChild(h)

    dom.appendChild(div)
  }

  checkCallback()

  function checkCallback () {
    callbackCount--

    if (callbackCount === 0) {
      div.classList.remove('loading')
      callback(null)
    }
  }
})
