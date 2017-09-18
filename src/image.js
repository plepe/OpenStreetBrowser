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
  var div = document.createElement('div')
  div.className = 'images'

  if (data.object.tags.image) {
    var img = data.object.tags.image

    if (img.indexOf('File:') === 0) {
      showWikimediaImage(img.substr(5), div)
      found++
    } else if (img.indexOf('http://commons.wikimedia.org/wiki/File:') === 0) {
      showWikimediaImage(decodeURIComponent(img.substr(39)), div)
      found++
    } else if (img.indexOf('https://commons.wikimedia.org/wiki/File:') === 0) {
      showWikimediaImage(decodeURIComponent(img.substr(40)), div)
      found++
    } else {
      showImage(img, div)
      found++
    }
  }

  if (data.object.tags.wikidata) {
    found++

    wikidata.load(data.object.tags.wikidata, function (err, result) {
      if (result.claims && result.claims.P18) {
        result.claims.P18.forEach(function (d) {
          showWikimediaImage(d.mainsnak.datavalue.value, div)
        })
      }
    })
  }

  if (found) {
    h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('images')))
    dom.appendChild(h)
    dom.appendChild(div)
  }

  callback(null)
})
