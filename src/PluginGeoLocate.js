register_hook('init', function () {
  // Geo location
  L.control.locate({
    locateOptions: {
      enableHighAccuracy: true,
      maxZoom: 17
    },
    flyTo: true,
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
