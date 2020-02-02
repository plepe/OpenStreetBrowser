const turf = {
  area: require('@turf/area').default,
  length: require('@turf/length').default
}
const tabs = require('modulekit-tabs')

const formatUnits = require('./formatUnits')
require('./GeoInfo.css')

function formatCoord (coord) {
  return coord.lat.toFixed(5) + ' ' + coord.lng.toFixed(5)
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

  let domZoom = document.createElement('div')
  tab.content.appendChild(domZoom)

  let domBBoxNW = document.createElement('div')
  tab.content.appendChild(domBBoxNW)

  let domCenter = document.createElement('div')
  tab.content.appendChild(domCenter)

  let domBBoxSE = document.createElement('div')
  tab.content.appendChild(domBBoxSE)

  let domMouse = document.createElement('div')
  tab.content.appendChild(domMouse)

  let domLocation = document.createElement('div')
  tab.content.appendChild(domLocation)

  global.map.on('move', () => {
    let scale = global.map.getMetersPerPixel().toPrecision(3)
    if (scale.match(/E/i)) {
      scale = parseFloat(scale)
    }

    domZoom.innerHTML = '<span title="' + lang('geoinfo:zoom') + '"><i class="fas fa-search-location icon"></i>z' + Math.round(global.map.getZoom()) + ', ' + scale + 'm/px</span>'

    let bounds = map.getBounds()
    domBBoxNW.innerHTML = '<span title="' + lang('geoinfo:nw-corner') + '"><span class="icon">▛</span>' + formatCoord(bounds.getNorthWest().wrap()) + '</span>'
    domCenter.innerHTML = '<span title="' + lang('geoinfo:center') + '"><i class="fas fa-crosshairs icon"></i>' + formatCoord(bounds.getCenter().wrap()) + '</span>'
    domBBoxSE.innerHTML = '<span title="' + lang('geoinfo:se-corner') + '"><span class="icon">▟</span>' + formatCoord(bounds.getSouthEast().wrap()) + '</span>'
    updateTabHeader(tab.header)
  })

  global.map.on('mousemove', (e) => {
    domMouse.innerHTML = '<span title="' + lang('geoinfo:mouse') + '"><i class="fas fa-mouse-pointer icon"></i>' + formatCoord(e.latlng.wrap()) + '</span>'
  })

  global.map.on('mouseout', (e) => {
    domMouse.innerHTML = ''
  })

  global.map.on('locationfound', (e) => {
    domLocation.innerHTML = '<span title="' + lang('geoinfo:location') + '"><i class="fas fa-map-marker-alt"></i> ' + formatCoord(e.latlng.wrap()) + '</span>'
  })
})

register_hook('show-details', (data, category, dom, callback) => {
  let div = document.createElement('div')
  dom.appendChild(div)

  let ob = data.object
  let result = '<div class="geo-info"><h3>' + lang('geoinfo:header') + '</h3>'

  let geojson = ob.GeoJSON()
  let area = turf.area(geojson)
  let length = turf.length(geojson) * 1000

  if (area !== 0 || length !== 0) {
    result += '<div><i class="fas fa-shapes icon"></i>' +
      lang('geoinfo:length') + ': ' + formatUnits.distance(length) +
      (area === 0 ? '' : ', ' + lang('geoinfo:area') + ': ' + formatUnits.area(area)) +
      '</div>'
  }

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div title="' + lang('geoinfo:nw-corner') + '"><span class="icon">▛</span>' + formatCoord({ lat: ob.bounds.minlat, lng: ob.bounds.maxlon }) + '</div>'
  }

  result += '<div title="' + lang('geoinfo:center') + '"><i class="fas fa-crosshairs icon"></i>' + formatCoord({ lat: ob.center.lat, lng: ob.center.lon }) + '</div>'

  if (ob.bounds.minlat !== ob.bounds.maxlat || ob.bounds.minlon !== ob.bounds.maxlon) {
    result += '<div title="' + lang('geoinfo:se-corner') + '"><span class="icon">▟</span>' + formatCoord({ lat: ob.bounds.maxlat, lng: ob.bounds.minlon }) + '</div>'
  }

  result += '</div>'
  div.innerHTML = result

  callback()
})

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
