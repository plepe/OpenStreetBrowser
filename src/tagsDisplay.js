const OverpassLayer = require('overpass-layer')

const formatter = [
  {
    regexp: /^(.*:)?wikidata$/,
    link: 'https://wikidata.org/wiki/{{ value|url_encode }}'
  },
  {
    regexp: /^(.*:)wikipedia$/,
    link: '{% set v = value|split(":") %}https://{{ v[0]|url_encode }}.wikipedia.org/wiki/{{ v[1]|replace({" ": "_"}) }}'
  },
  {
    regexp: /^(website|url|contact:website)$/,
    link: '{{ value|websiteUrl }}'
  },
  {
    regexp: /^(image|wikimedia_commons)$/,
    link: '{% if value matches "/^(File|Category):/" %}' +
      'https://commons.wikimedia.org/wiki/{{ value|replace({" ": "_"}) }}' +
      '{% else %}' +
      '{{ value }}' +
      '{% endif %}'
  }
]

let compiled = false
let defaultTemplate

module.exports = function tagsDisplay (tags) {
  if (!compiled) {
    defaultTemplate = OverpassLayer.twig.twig({ data: '{{ value }}', autoescape: true })
    for (let i in formatter) {
      if (formatter[i].format) {
        formatter[i].template = OverpassLayer.twig.twig({ data: formatter[i].format, autoescape: true })
      } else {
        formatter[i].template = OverpassLayer.twig.twig({ data: '<a target="_blank" href="' + formatter[i].link + '">{{ value }}</a>', autoescape: true })
      }
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

    let value = tags[k].split(/;/g)
    value = value.map(v => {
      // trim whitespace (but add it around the formatted value later)
      let m = v.match(/^( *)([^ ].*[^ ]|[^ ])( *)$/)
      if (m) {
        return m[1] + template.render({ value: m[2] }) + m[3]
      }
      return v
    }).join(';')

    dd.innerHTML = value
    div.appendChild(dd)
  }

  return div
}
