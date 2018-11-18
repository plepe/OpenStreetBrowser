class ExportGeoJSON {
  constructor (conf) {
    this.conf = conf
  }

  each (ob, callback) {
    callback(null, ob.object.GeoJSON(this.conf))
  }

  finishOne (object) {
    return {
      content: JSON.stringify(object, null, '    '),
      fileType: 'application/json',
      extension: 'geojson'
    }
  }

  finish (list) {
    if (list.length) {
      list = {
        type: 'FeatureCollection',
        features: list
      }
    }

    return {
      content: JSON.stringify(list, null, '    '),
      fileType: 'application/json',
      extension: 'geojson'
    }
  }
}

module.exports = ExportGeoJSON
