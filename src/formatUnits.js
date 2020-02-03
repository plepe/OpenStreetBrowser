const { measureFrom } = require('measure-ts')
const formatcoords = require('formatcoords')

const settings = {
  coordFormat: 'FFf',
  coordSpacer: ', ',
  system: 'si'
}

const distanceUnits = {
  si: ['cm', 'm', 'km'],
  imp: ['in', 'ft', 'yd', 'mi'],
  nautical: ['M'],
  m: ['m']
}

const areaUnits = {
  si: ['cm2', 'm2', 'ha', 'km2'],
  imp: ['in2', 'ft2', 'yd2', 'ac', 'mi2'],
  nautical: ['M2'],
  m: ['m2']
}

module.exports = {
  distance: value => {
    const measure = measureFrom.apply(this, distanceUnits[settings.system])
    return measure(value).toString()
  },
  area: value => {
    const measure = measureFrom.apply(this, areaUnits[settings.system])
    return measure(value).toString()
  },
  coord: value => formatcoords(value).format(settings.coordFormat, {
    latLonSeparator: settings.coordSpacer
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
      'nautical': lang('formatUnits:system:nautical'),
      'm': lang('formatUnits:system:m'),
    },
    'default': settings.system
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
    'default': settings.coordFormat
  }

  def.formatUnitsCoordSpacer = {
    'name': lang('formatUnits:coordSpacer'),
    'type': 'select_other',
    'values': {
      ' ': 'Space',
      ', ': 'Colon'
    },
    'default': settings.coordSpacer
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

  settings.coordFormat = global.options.formatUnitsCoordFormat || settings.coordFormat
  settings.coordSpacer = global.options.formatUnitsCoordSpacer || settings.coordSpacer
  settings.system = global.options.formatUnitsSystem || settings.system

  if (old !== JSON.stringify(settings)) {
    call_hooks('format-units-refresh')
  }
})
