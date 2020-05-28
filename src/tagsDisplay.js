const OverpassLayer = require('overpass-layer')

const httpGet = require('./httpGet')

const formatter = [
  {
    regexp: /^(.*:)?wikidata$/,
    link: 'https://wikidata.org/wiki/{{ value }}'
  },
  {
    regexp: /^(.*:)?wikipedia$/,
    link: '{% set v = value|split(":") %}https://{{ v[0] }}.wikipedia.org/wiki/{{ v[1]|replace({" ": "_"}) }}'
  },
  {
    regexp: /^(.*:)?wikipedia:([a-zA-Z]+)$/,
    link: '{% set v = key|matches(":([a-zA-Z]+)") %}https://{{ v[1] }}.wikipedia.org/wiki/{{ value|replace({" ": "_"}) }}'
  },
  {
    regexp: /^((.*:)?website(:.*)?|(.*:)?url(:.*)?|contact:website)$/,
    link: '{{ value|websiteUrl }}'
  },
  {
    regexp: /^(image|wikimedia_commons)$/,
    link: '{% if value matches "/^(File|Category):/" %}' +
      'https://commons.wikimedia.org/wiki/{{ value|replace({" ": "_"}) }}' +
      '{% else %}' +
      '{{ value|websiteUrl }}' +
      '{% endif %}'
  },
  {
    regexp: /^(species)$/,
    link: 'https://species.wikimedia.org/wiki/{{ value|replace({" ": "_"}) }}'
  },
  {
    regexp: /^(phone|contact:phone|fax|contact:fax)(:.*|)$/,
    link: 'tel:{{ value }}'
  },
  {
    regexp: /^(email|contact:email)(:.*|)$/,
    link: 'mailto:{{ value }}'
  }
]

let tag2link
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
        return m[1] + template.render({ key: k, value: m[2] }) + m[3]
      }
      return v
    }).join(';')

    dd.innerHTML = value
    div.appendChild(dd)
  }

  return div
}

register_hook('init_callback', (initState, callback) => {
  httpGet('dist/tag2link.json', {}, (err, result) => {
    if (err) {
      console.error('Can\'t read dist/tag2link.json - execute bin/download_dependencies')
      return callback()
    }

    tag2link = JSON.parse(result.body)

    Object.keys(tag2link).forEach(key => {
      let tag = tag2link[key]
      let link = tag.formatter[0].link.replace('$1', '{{ value }}')

      if (tag.formatter.length > 1) {
        link = "#\" onclick=\"return tag2link(this, " + JSON.stringify(key).replace(/"/g, '&quot;') + ", {{ value|json_encode }})"
      }

      formatter.push({
        regexp: new RegExp("^" + key + "$"),
        link
      })
    })

    callback()
  })
})

global.tag2link = function (dom, key, value) {
  let div = document.createElement('div')
  div.className = 'tag2link'
  dom.parentNode.appendChild(div)

  let closeButton = document.createElement('div')
  closeButton.className = 'closeButton'
  closeButton.innerHTML = '❌'
  closeButton.onclick = () => {
    dom.parentNode.removeChild(div)
  }
  div.appendChild(closeButton)

  let selector = document.createElement('ul')
  div.appendChild(selector)

  let tag = tag2link[key]
  tag.formatter.forEach(formatter => {
    let li = document.createElement('li')

    let a = document.createElement('a')
    a.target = '_blank'
    a.href = formatter.link.replace('$1', value)
    a.appendChild(document.createTextNode(formatter.operator))

    li.appendChild(a)
    selector.appendChild(li)
  })

  return false
}
