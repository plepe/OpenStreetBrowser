const formatUnits = require('./formatUnits')

let control
let unitSystems = {
  si: 'metres',
  imp: 'landmiles',
  nautical: 'nauticalmiles',
  m: 'metres'
}

register_hook('init', function () {
  // Measurement plugin
  if (L.control.polylineMeasure) {
    control = L.control.polylineMeasure({
      unit: unitSystems[formatUnits.settings.system]
    }).addTo(map)
  }
})

register_hook('format-units-refresh', () => {
  if (control) {
    control.options.unit = unitSystems[formatUnits.settings.system]
  }
})
