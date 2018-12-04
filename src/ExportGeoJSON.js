class ExportGeoJSON {
  constructor (conf) {
    this.conf = conf
  }

  each (ob, callback) {
    ob.object.exportGeoJSON(this.conf, callback)
  }

  finishOne (object) {
    return {
      content: JSON.stringify(object, null, '    '),
      fileType: 'application/json',
      extension: 'geojson'
    }
  }

  finish (list) {
    if (!this.conf.singleFeature) {
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
