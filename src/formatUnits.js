var OverpassLayer = require('overpass-layer')
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

const coordsPrecisionFormats = {
  'FFf': [
    'DD X',
    'DD MM X',
    'DD MM X',
    'DD MM ss0 X',
    'DD MM ss1 X',
    'DD MM ss2 X',
    'DD MM ss3 X'
  ],
  'Ff': [
    'DD X',
    'DD MM X',
    'DD MM X',
    'DD mm1 X',
    'DD mm2 X',
    'DD mm3 X',
    'DD mm4 X'
  ],
  'f': [
    'DD X',
    'dd1 X',
    'dd2 X',
    'dd3 X',
    'dd4 X',
    'dd5 X',
    'dd6 X'
  ]
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
  coord: (value, options = {}) => {
    let format = settings.coordFormat
    options.precision = 'precision' in options ? options.precision : 5
    let decimalPlaces = 'decimalPlaces' in options ? options.decimalPlaces : options.precision

    if ('precision' in options && settings.coordFormat in coordsPrecisionFormats) {
      if (options.precision > coordsPrecisionFormats[settings.coordFormat].length) {
        format = coordsPrecisionFormats[settings.coordFormat][coordsPrecisionFormats[settings.coordFormat].length - 1]
      } else {
        format = coordsPrecisionFormats[settings.coordFormat][options.precision]
      }
    }

    let m = format.match(/^(.*)(s|m|d)([0-9]+)(.*)$/)
    if (m) {
      format = m[1] + m[2] + m[4]
      decimalPlaces = parseInt(m[3])
    }

    return formatcoords(value).format(format, {
      latLonSeparator: settings.coordSpacer,
      decimalPlaces
    })
  },
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

OverpassLayer.twig.extendFunction('formatDistance', function () {
  return module.exports.distance.call(this, arguments[0])
})
