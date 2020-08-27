const async = require('async')

const listDef = require('./wikipediaMonumentListDef.json')
const wikipediaGetImageProperties = require('./wikipediaGetImageProperties')
const stripLinks = require('./stripLinks')
const loadingIndicator = require('./loadingIndicator')

const cache = {}
const callbacks = {}

function callCallbacks(cbs, err, result) {
  cbs.forEach((cb, i) => {
    cb(err, result)
    cbs[i] = null
  })
}

function load (def, value, callback) {
  if (!def._id) {
    def._id = Math.random()
  }

  if (!(def._id in cache)) {
    cache[def._id] = {}
    callbacks[def._id] = {}
  }

  if (value in callbacks[def._id]) {
    callbacks[def._id][value].push(callback)
    return
  }

  callbacks[def._id][value] = [callback]

  let search = 'hastemplate:"' + def.searchTemplate + '" insource:/' + def.searchTemplate + '.*' + def.searchIdField + ' *= *' + value + '[^0-9]/ intitle:"' + def.searchTitle + '"'
  ajax('wikipediaSearch',
    {
      wikipedia: def.wikipedia,
      search
    }, (result) => {
      if (!result) {
        return callCallbacks(callbacks[def._id][value])
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
        return callCallbacks(callbacks[def._id][value])
      }

      const ret = {}

      if ('tableColumnImage' in def) {
        let imgs = tr.cells[def.tableColumnImage].getElementsByTagName('img')
        imgs = Array.from(imgs)

        // ignore icons
        imgs = imgs.filter(img => img.width > 64 && img.height > 64)

        if (imgs.length) {
          ret.image = wikipediaGetImageProperties(imgs[0])
          if (ret.image) {
            ret.image.src = imgs[0].src
          } else {
            ret.image = {
              src: imgs[0].src
            }
          }
        }
      }

      let td = tr.cells[def.tableColumnDescription]
      stripLinks(td)
      ret.description = td.innerHTML

      let m = result.page.split(/:/)
      ret.url = 'https://' + m[0] + '.wikipedia.org/wiki/' + m[1] + '#' + (def.tableIdPrefix || '') + value

      cache[def._id][value] = ret
      callCallbacks(callbacks[def._id][value], null, ret)
    }
  )
}

function show (result, div) {
  let text = ''

  if (!result) {
    return
  }

  if (result.image) {
    text += '<a target="_blank" href="https://commons.wikimedia.org/wiki/File:' + result.image.id + '"><img class="thumbimage" src="' + result.image.src + '"></a>'
  }

  text += result.description

  text += ' <a target="_blank" href="' + result.url + '">' + lang('more') + '</a>'

  let d = document.createElement('p')
  d.innerHTML = text
  div.appendChild(d)
}

function findListTags (data) {
  return Object.keys(listDef).map(key => {
    if (data.object.tags[key]) {
      let langs = Object.keys(listDef[key])

      // TODO: choose preferred language
      let lang = langs[0]

      return [listDef[key][lang], data.object.tags[key]]
    }
  }).filter(f => f)
}

module.exports = function (data, dom, callback) {
  let div = document.createElement('div')
  let indicator = loadingIndicator(div)

  let listTags = findListTags(data)

  if (listTags.length) {
    dom.appendChild(div)

    async.each(listTags,
      (ref, done) => {
        load(ref[0], ref[1], (err, result) => {
          if (err) {
            console.error(err)
            return done()
          }

          show(result, div)

          done()
        })
      },
      () => {
        indicator.end()
        callback()
      }
    )

    return true
  }
}

register_hook('image_sources', (sources, data) => {
  let listTags = findListTags(data)

  listTags.forEach(ref => sources.push({
    type: 'fun',
    fun: done => load(ref[0], ref[1], (err, result) => {
      if (err) { return console.error(err) }
      done(null, result.image)
    })
  }))
})
