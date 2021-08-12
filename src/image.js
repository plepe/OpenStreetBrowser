const hooks = require('modulekit-hooks')

var httpGet = require('./httpGet')
var ImageLoader = require('./ImageLoader')
const displayBlock = require('./displayBlock')
var showTimer

function showImage (image, dom) {
  var a = document.createElement('a')
  a.target = '_blank'
  a.href = image.id

  let img = document.createElement('img')
  img.src = image.id
  a.appendChild(img)

  dom.appendChild(a)

  return img
}

function showWikimediaImage (image, options, dom) {
  let img = document.createElement('img')

  if (!options.size) {
    options.size = 800
  }

  httpGet(
    'https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(image.id),
    {
      forceServerLoad: true
    },
    function (err, result) {
      if (err || !result) {
        return
      }

      let m = result.body.match(/<a href="([^"]+\/)([0-9]+)(px-[^"\/]+)" class="mw-thumbnail-link"/)
      if (m) {
        let src = m[1] + options.size + m[3]
        let srcset = m[1] + options.size + m[3] + ' 1x, ' +
          m[1] + Math.ceil(options.size * 1.5) + m[3] + ' 1.5x, ' +
          m[1] + Math.ceil(options.size * 2) + m[3] + ' 2x'

        let a = document.createElement('a')
        a.target = '_blank'
        a.href = 'https://commons.wikimedia.org/wiki/File:' + encodeURIComponent(image.id)

        img.src = src
        img.srcset = srcset
        a.appendChild(img)

        dom.appendChild(a)
      }
    }
  )

  return img
}

// feature: { id: 'File:xxx.jpg', type: 'wikimedia|url', url: 'https://...' }
function show (img, options, div) {
  div.innerHTML = ''

  switch (img.type) {
    case 'wikimedia':
      return showWikimediaImage(img, options, div)
      break
    case 'url':
      return showImage(img, div)
      break
    default:
  }
}

hooks.register('show-details', function (data, category, dom, callback) {
  displayImages(data, category, dom, callback, 'details')
})

hooks.register('show-popup', function (data, category, dom, callback) {
  displayImages(data, category, dom, callback, 'popup')
})

function displayImages(data, category, dom, callback, displayId) {
  var div = document.createElement('div')
  div.className = 'images'
  var imageWrapper
  var nextImageWrapper = document.createElement('div')
  let options

  if (showTimer) {
    window.clearInterval(showTimer)
  }

  var currentLoader = new ImageLoader(data)

  data.detailsImageCounter = {}

  currentLoader.next({
    counter: data.detailsImageCounter,
    wrap: true
  }, function (err, data) {
    if (!data) {
      return callback(err)
    }

    const block = displayBlock({
      dom,
      content: div,
      title: displayId === 'popup' ? null : lang('images'),
      order: displayId === 'popup' ? -1 : 2
    })
    block.classList.add('empty')

    imageWrapper = document.createElement('div')
    imageWrapper.className = 'imageWrapper'
    div.appendChild(imageWrapper)

    options = {
      size: Math.max(imageWrapper.offsetWidth, imageWrapper.offsetHeight)
    }

    let img = show(data, options, imageWrapper)
    if (img) {
      img.onload = () => {
        block.classList.remove('empty')
        dom.classList.add('hasImage')
      }
    }

    if (displayId === 'details') {
      showTimer = window.setInterval(showNext, 5000)
      loadNext()
    }
  })

  function loadNext () {
    currentLoader.next({
      counter: data.detailsImageCounter,
      wrap: true
    }, function (err, data) {
      if (err) {
        return console.log("Can't load next image", err)
      }

      show(data, options, nextImageWrapper)
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
}

hooks.register('hide-details', function () {
  if (showTimer) {
    window.clearInterval(showTimer)
  }
})
