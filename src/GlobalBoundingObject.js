const ee = require('event-emitter')
const OverpassLayer = require('overpass-layer')
const turf = {
  buffer: require('@turf/buffer'),
  intersect: require('@turf/intersect').default
}

class GlobalBoundingObject {
  // should extend OverpassLayer.BoundingObject -- does not work because of
  // babel issue
  constructor (map) {
    this.map = map
    this.config = { object: null, buffer: 100 }
    this.objects = {}

    this.mapView = new OverpassLayer.MapView(map)

    this.mapView.on('update', this.updateMap.bind(this))
    this.map.on('mousemove', this.updateMouse.bind(this))
  }

  setCustomObjects (objects) {
    this.objects = objects
  }

  setConfig (config) {
    this.config = config
    this.emit('update')
  }

  get () {
    if (this.config.object === null || this.config.object === 'viewport') {
      return this.mapView.get()
    }

    let geometry
    if (this.config.object === 'mouse') {
      if (!this.mousePos) {
        return null
      }

      geometry = {
        type: 'Feature',
        geometry: {
          type: 'Point',
          coordinates: [ this.mousePos.lng, this.mousePos.lat ]
        }
      }
    } else {
      let object = this.objects[this.config.object]
      if (!object) {
        return null
      }

      geometry = object.GeoJSON()
    }

    geometry = turf.buffer(geometry, this.config.buffer / 1000)

    if (this.config.options.includes('cropView')) {
      let mapView = this.mapView.get()
      geometry = turf.intersect(geometry, {
        type: 'Feature',
        geometry: {
          type: 'Polygon',
          coordinates: [[
            [ mapView.minlon, mapView.minlat ],
            [ mapView.maxlon, mapView.minlat ],
            [ mapView.maxlon, mapView.maxlat ],
            [ mapView.minlon, mapView.maxlat ],
            [ mapView.minlon, mapView.minlat ]
          ]]
        }
      })
    }

    return geometry
  }

  updateMap (e) {
    if (this.config.object === null || this.config.object === 'viewport' || this.config.options.includes('cropView')) {
      this.emit('update', e)
    }
  }

  updateMouse (e) {
    this.mousePos = e.latlng
    if (this.config.object === 'mouse') {
      this.emit('update', e)
    }
  }

  remove () {
  }
}

ee(GlobalBoundingObject.prototype)
module.exports = GlobalBoundingObject
