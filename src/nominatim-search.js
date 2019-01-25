const tabs = require('modulekit-tabs')
const httpGet = require('./httpGet')
require('./nominatim-search.css')

let tab
let input
let domResults

function show (data) {
  while(domResults.lastChild) {
    domResults.removeChild(domResults.lastChild)
  }

  data.forEach(
    entry => {
      let a = document.createElement('a')
      a.appendChild(document.createTextNode(entry.display_name))

      a.href = '#'
      a.onclick = () => {
        let bounds = new L.LatLngBounds(
          L.latLng(entry.boundingbox[0], entry.boundingbox[2]),
          L.latLng(entry.boundingbox[1], entry.boundingbox[3])
        )

        global.map.fitBounds(bounds, { animate: true })

        return false
      }

      let li = document.createElement('li')
      li.appendChild(a)
      
      domResults.appendChild(li)
    }
  )
}

function search (str) {
  httpGet(
    'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(str),
    {},
    (err, result) => {
      if (err) {
        return alert(err)
      }

      let data = JSON.parse(result.body)
      show(data)
    }
  )
}

register_hook('init', function () {
  tab = new tabs.Tab({
    id: 'search'
  })
  tab.content.classList.add('nominatim-search')
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-search" aria-hidden="true"></i>'
  tab.header.title = lang('search')

  let input = document.createElement('input')
  let inputTimer
  input.type = 'text'
  input.addEventListener('input', () => {
    if (inputTimer) {
      global.clearTimeout(inputTimer)
    }

    inputTimer = global.setTimeout(
      () => search(input.value),
      300
    )
  })

  tab.content.appendChild(input)

  domResults = document.createElement('ul')
  tab.content.appendChild(domResults)

  tab.on('select', () => input.focus())
})
