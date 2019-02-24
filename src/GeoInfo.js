const tabs = require('modulekit-tabs')

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

  tab.header.innerHTML = '<i class="fas fa-globe-europe"></i>'
  tab.header.title = lang('geo-info')

  let domCenter = document.createElement('div')
  tab.content.appendChild(domCenter)

  let domMouse = document.createElement('div')
  tab.content.appendChild(domMouse)

  let domLocation = document.createElement('div')
  tab.content.appendChild(domLocation)

  global.map.on('moveend', () => {
    domCenter.innerHTML = '<i class="fas fa-crosshairs"></i> ' + formatCoord(map.getCenter())
  })

  global.map.on('mousemove', (e) => {
    domMouse.innerHTML = '<i class="fas fa-mouse-pointer"></i> ' + formatCoord(e.latlng)
  })

  global.map.on('locationfound', (e) => {
    domLocation.innerHTML = '<i class="fas fa-map-marker-alt"></i> ' + formatCoord(e.latlng)
  })
})
