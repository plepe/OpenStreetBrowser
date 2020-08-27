const async = require('async')

const listDef = require('./wikipediaMonumentListDef.json')
const wikipediaGetImageProperties = require('./wikipediaGetImageProperties')

function show (def, value, div, callback) {
  let search = 'hastemplate:"' + def.searchTemplate + '" insource:/' + def.searchTemplate + '.*' + def.searchIdField + ' *= *' + value + '[^0-9]/ intitle:"' + def.searchTitle + '"'
  ajax('wikipediaSearch',
    {
      wikipedia: def.wikipedia,
      search
    }, (result) => {
      if (!result) {
        return callback()
      }

      var parse = document.createElement('div')
      parse.innerHTML = result.content

      let trId = (def.tableIdPrefix || '') + value
      let trs = parse.getElementsByTagName('tr')
      let tr

      for (let i = 0; i < trs.length; i++) {
        if (trs[i].id === trId) {
          tr = trs[i]
        }
      }

      if (!tr) {
        return callback()
      }

      let text = ''

      if (def.tableColumnImage) {
        let imgs = tr.cells[def.tableColumnImage].getElementsByTagName('img')
        if (imgs.length) {
          let file = wikipediaGetImageProperties(imgs[0])
          if (file) {
            text += '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + file.id + '"><img class="thumbimage" src="' + imgs[0].src + '"></a>'
          } else {
            text += '<img class="thumbimage" src="' + imgs[0].src + '">'
          }
        }
      }

      text += stripLinks(tr.cells[def.tableColumnDescription]).innerHTML

      let m = result.page.split(/:/)
      text += ' <a target="_blank" href="https://' + m[0] + '.wikipedia.org/wiki/' + m[1] + '#' + (def.tableIdPrefix || '') + value + '">' + lang('more') + '</a>'

      let d = document.createElement('div')
      d.innerHTML = text
      div.appendChild(d)

      callback()
    }
  )
}

module.exports = function (data, dom, callback) {
  let div = document.createElement('div')

  let functions = Object.keys(listDef).map(key => {
    if (data.object.tags[key]) {
      let langs = Object.keys(listDef[key])

      // TODO: choose preferred language
      let lang = langs[0]

      return show.bind(this, listDef[key][lang], data.object.tags[key], div)
    }
  }).filter(f => f)

  if (functions.length) {
    dom.appendChild(div)
    async.parallel(functions, callback)

    return true
  }
}
