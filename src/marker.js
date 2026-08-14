const state = require('./state')
const formatUnits = require('./formatUnits')

let marker
let markerPos
let markerText

register_hook('state-apply', function (state) {
  markerPos = null
  markerText = null

  if (state.marker) {
    const m = state.marker.match(/^(-?\d+(?:\.\d+)?)\/(-?\d+(?:\.\d+)?)(?:\/(.*))?$/)
    if (m) {
      markerText = m[3] ?? ''
      markerPos = [
        parseFloat(m[1]),
        parseFloat(m[2])
      ]

      update()
    }

    global.setTimeout(() => {
      // After loading a new marker, check if it visible - if not, fly to position
      const viewport = global.map.getBounds()
      if (!viewport.contains(markerPos)) {
        map.flyTo(markerPos)
      }
    }, 1)
  }
})

function update () {
  if (marker) {
    global.map.removeLayer(marker)
  }

  if (markerPos) {
    marker = L.marker(markerPos).addTo(global.map)
    popup = L.popup()

    marker.bindPopup(popup)

    popup.openCallback = (e) => {
      const dom = e.popup._contentNode
      dom.innerHTML = ''

      const closeButton = document.createElement('a')
      closeButton.setAttribute('data-order', -2000)
      closeButton.className = 'leaflet-popup-close-button'
      closeButton.innerHTML = '×'
      dom.insertBefore(closeButton, dom.firstChild)
      closeButton.onclick = () => {
        e.popup.close()
      }

      if (markerText) {
        const header = document.createElement('div')
        header.className = 'header'

        const title = document.createElement('div')
        title.className = 'title'
        title.appendChild(document.createTextNode(markerText))

        header.appendChild(title)
        dom.appendChild(header)
      }

      let block = document.createElement('div')
      block.className = 'block'

      const geoInfo = document.createElement('div')
      geoInfo.className = 'geo-info'
      block.appendChild(geoInfo)

      const objectCenter = document.createElement('div')
      objectCenter.className = 'object-center'
      geoInfo.appendChild(objectCenter)

      const value = document.createElement('div')
      value.className = 'value'
      objectCenter.appendChild(value)
      value.innerHTML = formatUnits.coord({ lat: markerPos[0], lng: markerPos[1] })

      dom.appendChild(block)

      block = document.createElement('div')
      block.className = 'block'

      const menu = document.createElement('ul')
      menu.className = 'footer'
      block.appendChild(menu)

      const share = document.createElement('li')
      share.className = 'shareLink'
      menu.appendChild(share)

      const link = document.createElement('a')
      link.href = '#marker=' + getParameter()
      link.innerHTML = lang('share')
      link.onclick = () => {
        navigator.clipboard.writeText(link.href)
        link.innerHTML = lang('copied-clipboard')
        global.setTimeout(() => link.innerHTML = lang('share'), 1000)
        return false
      }
      share.appendChild(link)

      const li = document.createElement('li')
      li.className = 'shareLink'
      menu.appendChild(li)

      const editLink = document.createElement('a')
      editLink.className = 'editLink'
      editLink.href = '#'
      editLink.innerHTML = lang('edit')
      li.appendChild(editLink)

      editLink.onclick = () => {
        edit(dom)
        return false
      }

      dom.appendChild(block)

      e.popup._contentNode.classList.add('objectDisplay')
    }
  }
}

function edit (dom) {
  let header = dom.querySelector('.header')
  if (!header) {
    header = document.createElement('div')
    header.className = 'header'
    dom.insertBefore(header, dom.firstChild)
  }

  header.innerHTML = ''

  const form = document.createElement('form')
  form.className = 'marker-edit-form'
  header.appendChild(form)

  const textarea = document.createElement('textarea')
  form.appendChild(textarea)
  textarea.value = markerText

  const submit = document.createElement('input')
  submit.type = 'submit'
  submit.value = lang('save')
  form.appendChild(submit)

  form.onsubmit = () => {
    markerText = textarea.value
    header.innerHTML = ''

    if (markerText) {
      const title = document.createElement('div')
      title.className = 'title'
      title.appendChild(document.createTextNode(markerText))

      header.appendChild(title)
    } else {
      dom.removeChild(header)
    }

    state.update(null, true)
  }
}

function getParameter () {
  let result = markerPos[0].toFixed(5) + '/' + markerPos[1].toFixed(5)

  if (markerText) {
    result += '/' + markerText
  }

  return result
}

register_hook('state-get', function (state) {
  if (markerPos) {
    state.marker = getParameter()
  }
})

function placeMarker (e) {
  markerPos = [ e.latlng.lat, e.latlng.lng ]
  markerText = null
  update()

  state.update(null, true)
}

register_hook('contextmenu-items', function (items) {
  items.push({
    text: lang('marker:place_marker'),
    callback: placeMarker
  })
})
