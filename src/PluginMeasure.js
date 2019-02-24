register_hook('init', function () {
  // Measurement plugin
  if (L.control.polylineMeasure) {
    L.control.polylineMeasure({
    }).addTo(map)
  }
})
