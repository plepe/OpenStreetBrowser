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
    this._update()
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

    if (!geometry) {
      return null
    }

    geometry = turf.buffer(geometry, this.config.buffer / 1000)

    if (!geometry) {
      return null
    }

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
      this._update(e)
    }
  }

  updateMouse (e) {
    this.mousePos = e.latlng
    if (this.config.object === 'mouse') {
      this._update(e)
    }
  }

  _update (e) {
    this.emit('update', e)
    this.updateMask()
  }

  updateMask () {
    if (this.mask) {
      map.removeLayer(this.mask)
      delete this.mask
    }

    let geometry = this.get()
    if (!geometry || geometry.type !== 'Feature') {
      return
    }

    this.mask = L.geoJson(geometry, {
      invert: true,
      style: {
        color: '#000000',
        opacity: 0.4,
        weight: 0
      }
    }).addTo(map)
  }

  remove () {
  }
}

ee(GlobalBoundingObject.prototype)
module.exports = GlobalBoundingObject
