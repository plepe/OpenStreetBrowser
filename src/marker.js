let marker
let markerPos
let markerText

register_hook('state-apply', function (state) {
  if (marker) {
    global.map.removeLayer(marker)
    markerPos = null
    markerText = null
  }

  if (state.marker) {
    markerPos = state.marker.split('/')
    markerText = markerPos[2]
    markerPos = [
      parseFloat(markerPos[0]),
      parseFloat(markerPos[1])
    ]

    marker = L.marker(markerPos).addTo(global.map)

    if (markerText) {
      marker.bindPopup(markerText)
    }
  }
})

register_hook('state-get', function (state) {
  if (markerPos) {
    state.marker = markerPos[0].toFixed(5) + '/' + markerPos[1].toFixed(5)

    if (markerText) {
      state.marker += '/' + markerText
    }
  }
})
