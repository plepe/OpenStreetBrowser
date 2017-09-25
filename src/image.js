var ImageLoader = require('./ImageLoader')
var showTimer

function showImage (url, dom) {
  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="' + url + '"><img src="' + url + '"></a>'

  dom.appendChild(div)
}

function showWikimediaImage (value, dom) {
  var url = 'https://commons.wikimedia.org/w/thumb.php?f=' + encodeURIComponent(value) + '&w=' + 235 // imgSize

  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(value) + '"><img src="' + url + '"/></a>'

  dom.appendChild(div)
}

// feature: { id: 'File:xxx.jpg', type: 'wikimedia|url', url: 'https://...' }
function show (img, options, div) {
  div.innerHTML = ''

  switch (img.type) {
    case 'wikimedia':
      showWikimediaImage(img.id, div)
      break
    case 'url':
      showImage(img.id, div)
      break
    default:
  }
}

register_hook('show-details', function (data, category, dom, callback) {
  var div = document.createElement('div')
  div.className = 'images loading'
  var imageWrapper

  dom.appendChild(div)

  if (showTimer) {
    window.clearInterval(showTimer)
  }

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  var currentLoader = ImageLoader(data)

  currentLoader.nextWrap(function (err, img) {
    div.classList.remove('loading')

    if (!img) {
      return callback(err)
    }

    var h = document.createElement('h3')
    h.appendChild(document.createTextNode(lang('images')))
    div.insertBefore(h, div.firstChild)

    imageWrapper = document.createElement('div')
    imageWrapper.className = 'imageWrapper'
    div.appendChild(imageWrapper)

    showTimer = window.setInterval(loadNext, 5000)

    show(img, {}, imageWrapper)
  })

  function loadNext () {
    currentLoader.nextWrap(function (err, img) {
      if (err) {
        return console.log("Can't show next image", err)
      }

      show(img, {}, imageWrapper)
    })
  }
})

register_hook('hide-details', function () {
  if (showTimer) {
    window.clearInterval(showTimer)
  }
})
