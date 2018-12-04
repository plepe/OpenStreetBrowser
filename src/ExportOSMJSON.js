class ExportOSMXML {
  constructor (conf) {
    this.conf = conf
    this.elements = {}
  }

  each (ob, callback) {
    ob.object.exportOSMJSON(this.conf, this.elements, callback)
  }

  finish (list) {
    return {
      content: JSON.stringify({
        version: '0.6',
        generator: 'OpenStreetBrowser',
        elements: Object.values(this.elements)
      }, null, '    '),
      fileType: 'application/json',
      extension: 'osm.json'
    }
  }
}

module.exports = ExportOSMXML
