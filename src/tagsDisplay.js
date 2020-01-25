const OverpassLayer = require('overpass-layer')

const formatter = [
  {
    regexp: /^(.*:)?wikidata$/,
    format: '<a target="_blank" href="https://wikidata.org/wiki/{{ value|url_encode }}">{{ value }}</a>'
  }
]

let compiled = false
let defaultTemplate

module.exports = function tagsDisplay (tags) {
  if (!compiled) {
    defaultTemplate = OverpassLayer.twig.twig({ data: '{{ value }}', autoescape: true })
    for (let i in formatter) {
      formatter[i].template = OverpassLayer.twig.twig({ data: formatter[i].format, autoescape: true })
    }

    compiled = true
  }

  const div = document.createElement('dl')
  div.className = 'tags'
  for (let k in tags) {
    const dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)

    let template = defaultTemplate

    const dd = document.createElement('dd')
    for (let i = 0; i < formatter.length; i++) {
      if (k.match(formatter[i].regexp)) {
        template = formatter[i].template
        break
      }
    }

    dd.innerHTML = template.render({ value: tags[k] })
    div.appendChild(dd)
  }

  return div
}
