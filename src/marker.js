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
      markerText = m[3]
      markerPos = [
        parseFloat(m[1]),
        parseFloat(m[2])
      ]

      update()
    }
  }
})

function update () {
  if (marker) {
    global.map.removeLayer(marker)
  }

  if (markerPos) {
    marker = L.marker(markerPos).addTo(global.map)
    if (markerText) {
      marker.bindPopup(markerText)
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
