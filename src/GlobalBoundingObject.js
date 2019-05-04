const ee = require('event-emitter')
const OverpassLayer = require('overpass-layer')
const turf = {
  buffer: require('@turf/buffer')
}

class GlobalBoundingObject {
  // should extend OverpassLayer.BoundingObject -- does not work because of
  // babel issue
  constructor (map) {
    this.map = map
    this.config = { object: null }

    this.mapView = new OverpassLayer.MapView(map)

    this.mapView.on('update', this.updateMap.bind(this))
    this.map.on('mousemove', this.updateMouse.bind(this))
  }

  setConfig (config) {
    this.config = config
    this.emit('update')
  }

  get () {
    if (this.config.object === 'mouse') {
      if (!this.mousePos) {
        return null
      }

      let b = turf.buffer({
        type: 'Feature',
        geometry: {
          type: 'Point',
          coordinates: [ this.mousePos.lng, this.mousePos.lat ]
        }
      }, 0.1)
      b = new BoundingBox(b) // TODO: remove, when GeoJSON support available
      return b
    } else {
      return this.mapView.get()
    }
  }

  updateMap (e) {
    if (this.config.object === null) {
      this.emit('update', e)
    }
  }

  updateMouse (e) {
    this.mousePos = e.latlng
    if (this.config.object === 'mouse') {
      this.emit('update', e)
    }
  }
}

ee(GlobalBoundingObject.prototype)
module.exports = GlobalBoundingObject
