var httpGet = require('./httpGet')
var ImageLoader = require('./ImageLoader')
var showTimer

function showImage (image, dom) {
  var div = document.createElement('div')
  div.innerHTML = '<a target="_blank" href="' + image.url + '"><img src="' + image.url + '"></a>'

  dom.appendChild(div)
}

function showWikimediaImage (image, dom) {
  httpGet(
    'https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(image.id),
    {
      forceServerLoad: true
    },
    function (err, result) {
      if (err || !result) {
        return
      }

      let m = result.body.match('img .* src="([^"]+)" .* data-file-width="([0-9]+)" data-file-height="([0-9]+)"')
      let src = m[1]

      var div = document.createElement('div')
      div.innerHTML = '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(image.id) + '"><img src="' + src + '"/></a>'

      dom.appendChild(div)
    }
  )
}

// feature: { id: 'File:xxx.jpg', type: 'wikimedia|url', url: 'https://...' }
function show (img, options, div) {
  div.innerHTML = ''

  switch (img.type) {
    case 'wikimedia':
      showWikimediaImage(img, div)
      break
    case 'url':
      showImage(img, div)
      break
    default:
  }
}

register_hook('show-details', function (data, category, dom, callback) {
  var div = document.createElement('div')
  div.className = 'images loading'
  var imageWrapper
  var nextImageWrapper = document.createElement('div')

  dom.appendChild(div)

  if (showTimer) {
    window.clearInterval(showTimer)
  }

  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  div.appendChild(l)

  var currentLoader = new ImageLoader(data)

  data.detailsImageCounter = {}

  currentLoader.next({
    counter: data.detailsImageCounter,
    wrap: true
  }, function (err, img) {
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

    showTimer = window.setInterval(showNext, 5000)

    show(img, {}, imageWrapper)
    loadNext()
  })

  function loadNext () {
    currentLoader.next({
      counter: data.detailsImageCounter,
      wrap: true
    }, function (err, img) {
      if (err) {
        return console.log("Can't load next image", err)
      }

      show(img, {}, nextImageWrapper)
    })
  }

  function showNext () {
    // when nothing was loaded, skip showing
    if (nextImageWrapper.firstChild) {
      while (imageWrapper.firstChild) {
        imageWrapper.removeChild(imageWrapper.firstChild)
      }

      while (nextImageWrapper.firstChild) {
        imageWrapper.appendChild(nextImageWrapper.firstChild)
      }
    }

    loadNext()
  }
})

register_hook('hide-details', function () {
  if (showTimer) {
    window.clearInterval(showTimer)
  }
})

register_hook('show-popup', function (data, category, dom, callback) {
  var div = document.createElement('div')
  div.className = 'images loading'
  var imageWrapper

  dom.insertBefore(div, dom.firstChild)

  var currentLoader = new ImageLoader(data)
  data.popupImageCounter = {}

  currentLoader.first({
    counter: data.popupImageCounter
  }, function (err, img) {
    div.classList.remove('loading')

    if (!img) {
      return callback(err)
    }

    imageWrapper = document.createElement('div')
    imageWrapper.className = 'imageWrapper'
    div.appendChild(imageWrapper)

    show(img, {}, imageWrapper)

    callback(null)
  })
})
