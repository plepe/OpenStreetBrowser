const state = require('./state')

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

      const closeButton = document.createElement('a')
      closeButton.setAttribute('data-order', -2000)
      closeButton.className = 'leaflet-popup-close-button'
      closeButton.innerHTML = '×'
      dom.insertBefore(closeButton, dom.firstChild)
      closeButton.onclick = () => {
        e.popup.close()
      }

      const header = document.createElement('div')
      header.className = 'header'

      const title = document.createElement('div')
      title.className = 'title'
      title.appendChild(document.createTextNode(markerText))

      header.appendChild(title)
      dom.appendChild(header)

      e.popup._contentNode.classList.add('objectDisplay')
    }
  }
}

register_hook('state-get', function (state) {
  if (markerPos) {
    state.marker = markerPos[0].toFixed(5) + '/' + markerPos[1].toFixed(5)

    if (markerText) {
      state.marker += '/' + markerText
    }
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
