const formatUnit = require('format-unit').default
const formatcoords = require('formatcoords')

module.exports = {
  distance: value => formatUnit('length')(value)(['mm', 'cm', 'm', 'km']),
  area: value => formatUnit('area')(value)(['cm2', 'm2', 'ha', 'km2']),
  coord: value => formatcoords(value).format()
}
