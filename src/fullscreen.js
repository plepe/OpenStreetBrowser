var FullscreenControl = L.Control.extend({
  options: {
    position: 'topleft'
    // control position - allowed: 'topleft', 'topright', 'bottomleft', 'bottomright'
  },
  onAdd: function (map) {
    var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control-fullscreen')
    container.innerHTML = "<a href='#'><i class='fa fa-arrows-alt'></i></a>"
    container.title = lang('toggle_fullscreen')

    const mapElement = document.body.querySelector('#map')
    mapElement.addEventListener('fullscreenchange', () => {
      if (document.fullscreenElement !== mapElement) {
        call_hooks('fullscreen-deactivate')
        document.body.classList.remove('fullscreen')
      }
    })

    container.onclick = function () {
      document.body.classList.toggle('fullscreen')

      if (options.fullscreenMode !== 'window') {
        document.body.classList.contains('fullscreen') ?
          mapElement.requestFullscreen() :
          document.exitFullscreen()
      }

      map.invalidateSize()

      call_hooks('fullscreen-' + (document.body.classList.contains('fullscreen') ? 'activate' : 'deactivate'))

      return false
    }

    return container
  }
})

register_hook('init', function (callback) {
  map.addControl(new FullscreenControl())
})

register_hook('show', function (url, options) {
  if (options.showDetails) {
    document.body.classList.remove('fullscreen')
  }
})

register_hook('options_form', (def) => {
  def.fullscreenMode = {
    name: lang('options:fullscreenMode'),
    type: 'select',
    placeholder: lang('default'),
    values: {
      'screen': lang('options:fullscreenMode:screen'),
      'window': lang('options:fullscreenMode:window'),
    }
  }
})
