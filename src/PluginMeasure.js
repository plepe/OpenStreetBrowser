const hooks = require('modulekit-hooks')

const formatUnits = require('./formatUnits')

let control
let unitSystems = {
  si: 'metres',
  imp: 'landmiles',
  nautical: 'nauticalmiles',
  m: 'metres'
}

hooks.register('init', function () {
  // Measurement plugin
  if (L.control.polylineMeasure) {
    control = L.control.polylineMeasure({
      unit: unitSystems[formatUnits.settings.system]
    }).addTo(map)
  }
})

hooks.register('format-units-refresh', () => {
  if (control) {
    control.options.unit = unitSystems[formatUnits.settings.system]
  }
})
