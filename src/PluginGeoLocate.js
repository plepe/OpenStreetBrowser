register_hook('init', function () {
  // Geo location
  L.control.locate({
    keepCurrentZoomLevel: true,
    drawCircle: true,
    drawMarker: false,
    showPopup: false
  }).addTo(map)
})
