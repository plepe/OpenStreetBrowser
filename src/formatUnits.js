const formatUnit = require('format-unit').default

module.exports = {
  distance: value => formatUnit('length')(value)(['mm', 'cm', 'm', 'km']),
  area: value => formatUnit('area')(value)(['cm2', 'm2', 'ha', 'km2']),
  coord: value => {
    return value.lat.toFixed(5) + ' ' + value.lng.toFixed(5)
  }
}
