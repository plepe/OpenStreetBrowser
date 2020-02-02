const formatUnit = require('format-unit').default
const formatcoords = require('formatcoords')

const defaults = {
  coordFormat: 'FFf',
  coordSpacer: ', ',
  system: 'si'
}
let settings = defaults

const distanceUnits = {
  si: ['cm', 'm', 'km'],
  imp: ['in', 'ft', 'yd', 'mi'],
  m: 'm'
}

const areaUnits = {
  si: ['cm2', 'm2', 'ha', 'km2'],
  imp: ['in2', 'ft2', 'yd2', 'ar', 'mi2'],
  m: 'm2'
}

module.exports = {
  distance: value => formatUnit('length')(value)(distanceUnits[global.options.formatUnitsSystem || defaults.system]),
  area: value => formatUnit('area')(value)(areaUnits[global.options.formatUnitsSystem || defaults.system]),
  coord: value => formatcoords(value).format(global.options.formatUnitsCoordFormat || defaults.coordFormat, {
    latLonSeparator: global.options.formatUnitsCoordSpacer || defaults.coordSpacer
  }),
  settings
}

register_hook('options_form', def => {
  def.formatUnitsSystem = {
    'name': lang('formatUnits:coordFormat'),
    'type': 'select',
    'values': {
      'si': lang('formatUnits:system:si'),
      'imp': lang('formatUnits:system:imp'),
      'm': lang('formatUnits:system:m'),
    },
    'default': defaults.system
  }

  def.formatUnitsCoordFormat = {
    'name': lang('formatUnits:coordFormat'),
    'desc': 'A format definition as specified in <a target="_blank" href="https://github.com/nerik/formatcoords">module formatCoords</a>',
    'type': 'select_other',
    'values': {
      'FFf': 'DD° MM′ SS.SSS″ X',
      'Ff': 'DD° MM.MMM′ X',
      'f': 'DD.DDD° X',
      'd': '±DD.DDD'
    },
    'default': defaults.coordFormat
  }

  def.formatUnitsCoordSpacer = {
    'name': lang('formatUnits:coordSpacer'),
    'type': 'select_other',
    'values': {
      ' ': 'Space',
      ', ': 'Colon'
    },
    'default': defaults.coordSpacer
  }
})

register_hook('options_save', data => {
  let old = JSON.stringify(settings)

  settings.coordFormat = data.formatUnitsCoordFormat
  settings.coordSpacer = data.formatUnitsCoordSpacer
  settings.system = data.formatUnitsSystem

  if (old !== JSON.stringify(settings)) {
    call_hooks('format-units-refresh')
  }
})

register_hook('init', () => {
  let old = JSON.stringify(settings)

  settings.coordFormat = global.options.formatUnitsCoordFormat || defaults.coordFormat
  settings.coordSpacer = global.options.formatUnitsCoordSpacer || defaults.coordSpacer
  settings.system = global.options.formatUnitsSystem || defaults.system

  if (old !== JSON.stringify(settings)) {
    call_hooks('format-units-refresh')
  }
})
