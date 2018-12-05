class ExportOSMXML {
  constructor (conf) {
    this.conf = conf
    this.parentNode = document.createElement('osm')
  }

  each (ob, callback) {
    ob.object.exportOSMXML(this.conf, this.parentNode, callback)
  }

  finish (list) {
    return {
      content: '<?xml version="1.0" encoding="UTF-8"?><osm version="0.6" generator="OpenStreetBrowser">' + this.parentNode.innerHTML + '</osm>',
      fileType: 'application/xml',
      extension: 'osm.xml'
    }
  }
}

module.exports = ExportOSMXML
