const fs = require('fs')
const osmtogeojson = require('osmtogeojson')
const DOMParser = require('@xmldom/xmldom').DOMParser

const parser = new DOMParser({
  errorHandler: {
    error: (err) => { throw new Error('Error parsing XML file: ' + err) },
    fatalError: (err) => { throw new Error('Error parsing XML file: ' + err) }
  }
})

// load and parse original file
const content = fs.readFileSync('data/boundaries.osm')
const input = parser.parseFromString(content.toString(), 'text/xml')

// convert to geojson
const output = osmtogeojson(input, {
  polygonFeatures: () => true
})

// remove ids (as they are fake anyway)
output.features.forEach(feature => {
  delete feature.id
  delete feature.properties.id
})

fs.writeFileSync('data/boundaries.geojson', JSON.stringify(output))
