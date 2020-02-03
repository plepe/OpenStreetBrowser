const turf = {
  area: require('@turf/area').default,
  length: require('@turf/length').default
}
const tabs = require('modulekit-tabs')

const formatUnits = require('./formatUnits')
require('./GeoInfo.css')

register_hook('init', function () {
  let tab = new tabs.Tab({
    id: 'search',
    weight: -1
  })
  tab.content.classList.add('geo-info')
  global.tabs.add(tab)

  updateTabHeader(tab.header)
  tab.header.title = lang('geoinfo:header')

  let domZoom = document.createElement('div')
  domZoom.title = lang('geoinfo:zoom')
  tab.content.appendChild(domZoom)

  let domBBoxNW = document.createElement('div')
  domBBoxNW.title = lang('geoinfo:nw-corner')
  tab.content.appendChild(domBBoxNW)

  let domCenter = document.createElement('div')
  domCenter.title = lang('geoinfo:center')
  tab.content.appendChild(domCenter)

  let domBBoxSE = document.createElement('div')
  domBBoxSE.title = lang('geoinfo:se-corner')
  tab.content.appendChild(domBBoxSE)

  let domMouse = document.createElement('div')
  domMouse.title = lang('geoinfo:mouse')
  domMouse.className = 'empty'
  tab.content.appendChild(domMouse)

  let domLocation = document.createElement('div')
  domLocation.title = lang('geoinfo:location')
  domLocation.className = 'empty'
  tab.content.appendChild(domLocation)

  function updateMapView () {
    let scale = formatUnits.distance(global.map.getMetersPerPixel())

    domZoom.innerHTML = '<i class="fas fa-search-location icon"></i><span class="value">z' + Math.round(global.map.getZoom()) + ', ' + scale + '/px</span>'

    let bounds = map.getBounds()
    domBBoxNW.innerHTML = '<img class="icon" src="img/geo-info-bbox-nw.svg"/><span class="value">' + formatUnits.coord(bounds.getNorthWest().wrap()) + '</span>'
    domCenter.innerHTML = '<img class="icon" src="img/geo-info-bbox-center.svg"/><span class="value">' + formatUnits.coord(bounds.getCenter().wrap()) + '</span>'
    domBBoxSE.innerHTML = '<img class="icon" src="img/geo-info-bbox-se.svg"/>' + formatUnits.coord(bounds.getSouthEast().wrap()) + '</span>'
    updateTabHeader(tab.header)
  }

  let lastMouseEvent
  function updateMouse (e) {
    if (!e) {
      e = lastMouseEvent
    }

    if (e) {
      domMouse.innerHTML = '<i class="fas fa-mouse-pointer icon"></i><span class="value">' + formatUnits.coord(e.latlng.wrap()) + '</span>'
      domMouse.className = ''
    } else {
      removeMouse()
    }

    lastMouseEvent = e
  }

  function removeMouse () {
    lastMouseEvent = null
    domMouse.innerHTML = ''
    domMouse.className = 'empty'
  }

  let lastLocation
  function updateLocation (e) {
    if (!e) {
      lastLocation = e
    }

    if (e) {
      domLocation.innerHTML = '<i class="fas fa-map-marker-alt icon"></i><span class="value">' + formatUnits.coord(e.latlng.wrap()) + '</span>'
      domLocation.className = ''
    }
  }

  global.map.on('move', updateMapView)
  global.map.on('mousemove', updateMouse)
  global.map.on('mouseout', removeMouse)
  global.map.on('locationfound', updateLocation)
  register_hook('format-units-refresh', updateMapView)
  register_hook('format-units-refresh', updateMouse)
  register_hook('format-units-refresh', removeMouse)
  register_hook('format-units-refresh', updateLocation)
})

let showDetailsCurrent
register_hook('show-details', (data, category, dom, callback) => {
  let div = document.createElement('div')
  dom.appendChild(div)

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
  let result = '<div class="geo-info"><h3>' + lang('geoinfo:header') + '</h3>'

  let geojson = ob.GeoJSON()
  let area = turf.area(geojson)
  let length = turf.length(geojson) * 1000

  if (area !== 0 || length !== 0) {
    result += '<div>' +
      '<img class="icon" src="img/geo-info-object-shape.svg"/><span class="value">' +
      lang('geoinfo:length') + ': ' + formatUnits.distance(length) +
      (area === 0 ? '' : ', ' + lang('geoinfo:area') + ': ' + formatUnits.area(area)) +
      '</span></div>'
  }

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div title="' + lang('geoinfo:nw-corner') + '"><img class="icon" src="img/geo-info-object-nw.svg"/><span class="value">' + formatUnits.coord({ lat: ob.bounds.minlat, lng: ob.bounds.maxlon }) + '</span></div>'
  }

  result += '<div title="' + lang('geoinfo:centroid') + '"><img class="icon" src="img/geo-info-object-center.svg"/><span class="value">' + formatUnits.coord({ lat: ob.center.lat, lng: ob.center.lon }) + '</span></div>'

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div title="' + lang('geoinfo:se-corner') + '"><img class="icon" src="img/geo-info-object-se.svg"/><span class="value">' + formatUnits.coord({ lat: ob.bounds.maxlat, lng: ob.bounds.minlon }) + '</span></div>'
  }

  result += '</div>'
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
