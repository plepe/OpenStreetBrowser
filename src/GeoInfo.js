const turf = {
  area: require('@turf/area').default,
  length: require('@turf/length').default
}
const tabs = require('modulekit-tabs')

const formatUnits = require('./formatUnits')
const displayBlock = require('./displayBlock')
require('./GeoInfo.css')

function heading (value) {
  return [ 'N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N' ][Math.round(value / 45)]
}

register_hook('init', function () {
  let tab = new tabs.Tab({
    id: 'search',
    weight: -1
  })
  tab.content.classList.add('geo-info')
  global.tabs.add(tab)

  updateTabHeader(tab.header)
  tab.header.title = lang('geoinfo:header')

  let crosshairIcon = L.icon({
    iconUrl: 'img/crosshair.png',
    iconSize:     [21, 21],
    iconAnchor:   [10, 10],
  })

  let crosshair = new L.marker([0,0], {
    icon: crosshairIcon,
    clickable:false,
    pane: "crosshair800"
  })

  global.map.createPane('crosshair800')
  global.map.getPane('crosshair800').style.zIndex = 800
  global.map.getPane('crosshair800').style.pointerEvents = 'none'

  let domZoom = document.createElement('div')
  domZoom.className = 'zoom'
  domZoom.title = lang('geoinfo:zoom')
  tab.content.appendChild(domZoom)

  let domBBoxNW = document.createElement('div')
  domBBoxNW.className = 'bbox-nw-corner'
  domBBoxNW.title = lang('geoinfo:nw-corner')
  tab.content.appendChild(domBBoxNW)

  let domCenter = document.createElement('div')
  domCenter.className = 'bbox-center'
  domCenter.title = lang('geoinfo:center')
  tab.content.appendChild(domCenter)

  let domBBoxSE = document.createElement('div')
  domBBoxSE.className = 'bbox-se-corner'
  domBBoxSE.title = lang('geoinfo:se-corner')
  tab.content.appendChild(domBBoxSE)

  let domMouse = document.createElement('div')
  domMouse.className = 'mouse empty'
  domMouse.title = lang('geoinfo:mouse')
  tab.content.appendChild(domMouse)

  let domLocation = document.createElement('div')
  domLocation.title = lang('geoinfo:location')
  domLocation.className = 'location empty'
  tab.content.appendChild(domLocation)

  function getPrecision () {
    let zoom = global.map.getZoom()
    return zoom > 16 ? 5
      : zoom > 8 ? 4
      : zoom > 4 ? 3
      : zoom > 2 ? 2
      : zoom > 1 ? 1
      : 0
  }

  function updateMapView () {
    crosshair.setLatLng(global.map.getCenter())

    let scale = formatUnits.distance(global.map.getMetersPerPixel())
    let scale2 = formatUnits.area(Math.pow(global.map.getMetersPerPixel(), 2))
    let precision = getPrecision()

    domZoom.innerHTML = '<span class="value">z' +
      Math.round(global.map.getZoom()) + ', ' +
      scale + '/px, ' +
      scale2 + '/px²' +
      '</span>'

    let bounds = map.getBounds()
    domBBoxNW.innerHTML = '<span class="value">' + formatUnits.coord(bounds.getNorthWest().wrap(), { precision }) + '</span>'
    domCenter.innerHTML = '<span class="value">' + formatUnits.coord(bounds.getCenter().wrap(), { precision }) + '</span>'
    domBBoxSE.innerHTML = '<span class="value">' + formatUnits.coord(bounds.getSouthEast().wrap(), { precision }) + '</span>'
  }

  let lastMouseEvent
  function updateMouse (e) {
    if (!e) {
      e = lastMouseEvent
    }

    if (e) {
      let precision = getPrecision()
      domMouse.innerHTML = '<span class="value">' + formatUnits.coord(e.latlng.wrap(), { precision }) + '</span>'
      domMouse.classList.remove('empty')
    } else {
      removeMouse()
    }

    lastMouseEvent = e
  }

  function removeMouse () {
    lastMouseEvent = null
    domMouse.innerHTML = ''
    domMouse.classList.add('empty')
  }

  let lastLocation
  function updateLocation (e) {
    if (e) {
      lastLocation = e
    } else {
      e = lastLocation
    }

    if (e) {
      domLocation.innerHTML = '<span class="value">' + formatUnits.coord(e.latlng.wrap(), { precision: 5 }) +
        (typeof e.accuracy !== 'undefined' ? (global.options.formatUnitsCoordSpacer || ', ') + '± ' + formatUnits.distance(e.accuracy.toFixed(0)) : '') + '<br/>' +
        (typeof e.altitude !== 'undefined' ? '<i class="fas fa-mountain"></i> ' + formatUnits.height(e.altitude) + (typeof e.altitudeAccuracy !== 'undefined' ? ' ± ' + formatUnits.distance(e.altitudeAccuracy) : '') + ' ' : '') +
        (typeof e.speed !== 'undefined' ? '<i class="fas fa-tachometer-alt"></i> ' + formatUnits.speed(e.speed) + ' ' : '') +
        (typeof e.heading !== 'undefined' ? '<i class="fas fa-compass"></i> ' + lang('heading:' + heading(e.heading)) + ' (' + e.heading.toFixed(0) + '°)' : '') +
	'</span>'
      domLocation.classList.remove('empty')
    }
  }

  function saveLocation (e) {
    lastLocation = e
  }

  global.map.on('move', () => {
    updateTabHeader(tab.header)
  })
  global.map.on('locationfound', saveLocation)

  tab.on('select', () => {
    crosshair.addTo(global.map)

    updateMapView()
    updateLocation()

    global.map.on('move', updateMapView)
    global.map.on('mousemove', updateMouse)
    global.map.on('mouseout', removeMouse)
    global.map.off('locationfound', saveLocation)
    global.map.on('locationfound', updateLocation)
  })

  tab.on('unselect', () => {
    crosshair.removeFrom(global.map)

    global.map.off('move', updateMapView)
    global.map.off('mousemove', updateMouse)
    global.map.off('mouseout', removeMouse)
    global.map.off('locationfound', updateLocation)
    global.map.on('locationfound', saveLocation)
  })

  register_hook('format-units-refresh', updateMapView)
  register_hook('format-units-refresh', updateMouse)
  register_hook('format-units-refresh', removeMouse)
  register_hook('format-units-refresh', updateLocation)
})

let showDetailsCurrent
register_hook('show-details', (data, category, dom, callback) => {
  let div = document.createElement('div')
  div.className = 'geo-info'

  displayBlock({
    dom,
    title: lang('geoinfo:header'),
    content: div,
    order: 5,
  })

  showDetailsCurrent = [ data, category, div ]
  geoInfoShowDetails.apply(this, showDetailsCurrent)
  callback()
})
register_hook('format-units-refresh', () => {
  if (showDetailsCurrent) {
    showDetailsCurrent[2].innerHTML = ''
    geoInfoShowDetails.apply(this, showDetailsCurrent)
  }
})

function geoInfoShowDetails (data, category, div) {
  let ob = data.object
  let result = ''

  let geojson = ob.GeoJSON()
  let area = turf.area(geojson)
  let length = turf.length(geojson) * 1000

  if (area !== 0 || length !== 0) {
    result += '<div class="object-shape">' +
      '<span class="value">' +
      lang('geoinfo:length') + ': ' + formatUnits.distance(length) +
      (area === 0 ? '' : ', ' + lang('geoinfo:area') + ': ' + formatUnits.area(area)) +
      '</span></div>'
  }

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div class="object-nw-corner" title="' + lang('geoinfo:nw-corner') + '"><span class="value">' + formatUnits.coord({ lat: ob.bounds.minlat, lng: ob.bounds.maxlon }) + '</span></div>'
  }

  result += '<div class="object-center" title="' + lang('geoinfo:centroid') + '"><span class="value">' + formatUnits.coord({ lat: ob.center.lat, lng: ob.center.lon }) + '</span></div>'

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div class="object-se-corner" title="' + lang('geoinfo:se-corner') + '"><span class="value">' + formatUnits.coord({ lat: ob.bounds.maxlat, lng: ob.bounds.minlon }) + '</span></div>'
  }

  div.innerHTML = result
}

function updateTabHeader (header) {
  if (!global.map._loaded) {
    return
  }

  let center = global.map.getCenter().wrap()
  if (center.lng < -35) {
    header.innerHTML = '<i class="fas fa-globe-americas"></i>'
  } else if (center.lng > 80) {
    header.innerHTML = '<i class="fas fa-globe-asia"></i>'
  } else if (center.lat < 30) {
    header.innerHTML = '<i class="fas fa-globe-africa"></i>'
  } else {
    header.innerHTML = '<i class="fas fa-globe-europe"></i>'
  }
}
