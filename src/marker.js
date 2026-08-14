const state = require('./state')
const formatUnits = require('./formatUnits')

let marker

class Marker {
  constructor (pos, text) {
    this.pos = pos
    this.text = text
  }

  getParameter () {
    let result = this.pos[0].toFixed(5) + '/' + this.pos[1].toFixed(5)

    if (this.text) {
      result += '/' + this.text
    }

    return result
  }

  show () {
    this.feature = L.marker(this.pos).addTo(global.map)
    this.popup = L.popup()

    this.feature.bindPopup(this.popup)
    this.popup.openCallback = (e) => {
      const dom = e.popup._contentNode
      dom.innerHTML = ''

      const closeButton = document.createElement('a')
      closeButton.setAttribute('data-order', -2000)
      closeButton.className = 'leaflet-popup-close-button'
      closeButton.innerHTML = '×'
      dom.insertBefore(closeButton, dom.firstChild)
      closeButton.onclick = () => {
        e.popup.close()
      }

      if (this.text) {
        const header = document.createElement('div')
        header.className = 'header'

        const title = document.createElement('div')
        title.className = 'title'
        title.appendChild(document.createTextNode(this.text))

        header.appendChild(title)
        dom.appendChild(header)
      }

      let block = document.createElement('div')
      block.className = 'block'

      const geoInfo = document.createElement('div')
      geoInfo.className = 'geo-info'
      block.appendChild(geoInfo)

      const objectCenter = document.createElement('div')
      objectCenter.className = 'object-center'
      geoInfo.appendChild(objectCenter)

      const value = document.createElement('div')
      value.className = 'value'
      objectCenter.appendChild(value)
      value.innerHTML = formatUnits.coord({ lat: this.pos[0], lng: this.pos[1] })

      dom.appendChild(block)

      block = document.createElement('div')
      block.className = 'block'

      const menu = document.createElement('ul')
      menu.className = 'footer'
      block.appendChild(menu)

      const share = document.createElement('li')
      share.className = 'shareLink'
      menu.appendChild(share)

      const link = document.createElement('a')
      link.href = '#marker=' + this.getParameter()
      link.innerHTML = lang('share')
      link.onclick = () => {
        navigator.clipboard.writeText(link.href)
        link.innerHTML = lang('copied-clipboard')
        global.setTimeout(() => link.innerHTML = lang('share'), 1000)
        return false
      }
      share.appendChild(link)

      const li = document.createElement('li')
      li.className = 'shareLink'
      menu.appendChild(li)

      const editLink = document.createElement('a')
      editLink.className = 'editLink'
      editLink.href = '#'
      editLink.innerHTML = lang('edit')
      li.appendChild(editLink)

      editLink.onclick = () => {
        this.edit(dom)
        return false
      }

      dom.appendChild(block)

      e.popup._contentNode.classList.add('objectDisplay')
    }
  }

  edit (dom) {
    let header = dom.querySelector('.header')
    if (!header) {
      header = document.createElement('div')
      header.className = 'header'
      dom.insertBefore(header, dom.firstChild)
    }

    header.innerHTML = ''

    const form = document.createElement('form')
    form.className = 'marker-edit-form'
    header.appendChild(form)

    const textarea = document.createElement('textarea')
    form.appendChild(textarea)
    textarea.value = this.text

    const submit = document.createElement('input')
    submit.type = 'submit'
    submit.value = lang('save')
    form.appendChild(submit)

    form.onsubmit = () => {
      this.text = textarea.value
      header.innerHTML = ''

      if (this.text) {
        const title = document.createElement('div')
        title.className = 'title'
        title.appendChild(document.createTextNode(this.text))

        header.appendChild(title)
      } else {
        dom.removeChild(header)
      }

      state.update(null, true)
    }
  }

  remove () {
    global.map.removeLayer(this.feature)
  }
}


register_hook('state-apply', function (state) {
  if (state.marker) {
    if (marker) {
      marker.remove()
    }

    const m = state.marker.match(/^(-?\d+(?:\.\d+)?)\/(-?\d+(?:\.\d+)?)(?:\/(.*))?$/)
    if (m) {
      const markerText = m[3] ?? ''
      const markerPos = [
        parseFloat(m[1]),
        parseFloat(m[2])
      ]

      marker = new Marker(markerPos, markerText)
      marker.show()
    }

    global.setTimeout(() => {
      // After loading a new marker, check if it visible - if not, fly to position
      const viewport = global.map.getBounds()
      if (!viewport.contains(marker.pos)) {
        map.flyTo(marker.pos)
      }
    }, 1)
  }
})

register_hook('state-get', function (state) {
  if (marker) {
    state.marker = marker.getParameter()
  }
})

function placeMarker (e) {
  if (marker) {
    marker.remove()
  }

  const markerPos = [ e.latlng.lat, e.latlng.lng ]
  const markerText = null
  marker = new Marker(markerPos, markerText)
  marker.show()

  state.update(null, true)
}

register_hook('contextmenu-items', function (items) {
  items.push({
    text: lang('marker:place_marker'),
    callback: placeMarker
  })
})
