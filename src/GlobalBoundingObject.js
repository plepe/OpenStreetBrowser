const ee = require('event-emitter')
const OverpassLayer = require('overpass-layer')

class GlobalBoundingObject {
  // should extend OverpassLayer.BoundingObject -- does not work because of
  // babel issue
  constructor (map) {
    this.map = map

    this.mapView = new OverpassLayer.MapView(map)

    this.mapView.on('update', this.update.bind(this))
  }

  get () {
    return this.mapView.get()
  }

  update (e) {
    this.emit('update', e)
  }
}

ee(GlobalBoundingObject.prototype)
module.exports = GlobalBoundingObject
