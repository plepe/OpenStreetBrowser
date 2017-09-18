var httpGet = require('./httpGet')

function wikidataLoad (id, callback) {
  httpGet('https://www.wikidata.org/wiki/Special:EntityData/' + id + '.json', function (err, result) {
    result = JSON.parse(result.body)

    if (!result.entities || !result.entities[id]) {
      console.log('invalid result', result)
      return callback(err, null)
    }

    callback(null, result.entities[id])
  })
}

module.exports = {
  load: wikidataLoad
}
