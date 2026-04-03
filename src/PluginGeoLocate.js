register_hook('map-init', function (map) {
  // Geo location
  L.control.locate({
    locateOptions: {
      enableHighAccuracy: true
    },
    flyTo: true,
    keepCurrentZoomLevel: true,
    initialZoomLevel: 17,
    drawCircle: true,
    circleStyle: {
      weight: 0,
      fillColor: '#ff0000'
    },
    markerStyle: {
      color: '#ff0000',
      fillColor: '#ff0000'
    },
    compassStyle: {
      color: '#ff0000',
      fillColor: '#ff0000'
    },
    showCompass: true,
    showPopup: false
  }).addTo(map)
})
