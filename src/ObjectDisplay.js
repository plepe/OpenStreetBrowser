const exportAll = require('./exportAll')
const tagsDisplay = require('./tagsDisplay').display
const displayBlock = require('./displayBlock')
const editLink = require('./editLink')

module.exports = class ObjectDisplay {
  constructor ({feature, category, dom, displayId, fallbackIds}, callback) {
    this.category = category
    this.displayId = displayId

    if (!fallbackIds) {
      fallbackIds = []
    }

    var div, h, dt, dd, li, a
    var k

    dom.innerHTML = ''

    let header = document.createElement('div')
    header.className = 'header'
    header.setAttribute('data-order', -1000)
    dom.appendChild(header)

    div = document.createElement('div')
    div.className = 'description'
    div.innerHTML = getProperty(feature.data, 'description', this.displayId, fallbackIds)

    header.appendChild(div)
    feature.sublayer.updateAssets(div, feature)

    div = document.createElement('div')
    div.className = 'title'
    div.innerHTML = getProperty(feature.data, 'title', this.displayId, fallbackIds)
    header.appendChild(div)
    feature.sublayer.updateAssets(div, feature)

    let body = document.createElement('div')
    body.className = 'body'

    let bodyCategory = document.createElement('div')
    body.appendChild(bodyCategory)
    let bodyTemplate = document.createElement('div')
    body.appendChild(bodyTemplate)

    let bodyBlock = displayBlock({
      dom,
      content: body,
      order: 0
    })

    this.updateListener = () => {
      bodyCategory.innerHTML = getProperty(feature.data, 'body', this.displayId, fallbackIds)
      feature.sublayer.updateAssets(bodyCategory, feature)

      category.renderTemplate(feature, this.displayId + 'Body', (err, result) => {
        bodyTemplate.innerHTML = result
        feature.sublayer.updateAssets(bodyTemplate, feature)

        if (bodyTemplate.innerHTML.match(/^\s*(<ul>\s*<\/ul>|)\s*$/) && bodyCategory.innerHTML.match(/^\s*(<ul>\s*<\/ul>|)\s*$/)) {
          bodyBlock.classList.add('empty')
        } else {
          bodyBlock.classList.remove('empty')
        }
      })
    }

    category.on('update', this.updateListener)
    this.updateListener()
    
    if (['details'].includes(this.displayId)) {
      displayBlock({
        dom,
        content: exportAll(feature),
        title: lang('header:export'),
        order: 5
      })
    }

    if (['details'].includes(this.displayId)) {
      displayBlock({
        dom,
        content: tagsDisplay(feature.object.tags),
        title: lang('header:attributes'),
        order: 10
      })

      div = document.createElement('dl')
      div.className = 'meta'
      dt = document.createElement('dt')
      dt.appendChild(document.createTextNode('id'))
      div.appendChild(dt)
      dd = document.createElement('dd')
      a = document.createElement('a')
      a.appendChild(document.createTextNode(feature.object.type + '/' + feature.object.osm_id))
      a.href = config.urlOpenStreetMap + '/' + feature.object.type + '/' + feature.object.osm_id
      a.target = '_blank'

      dd.appendChild(a)
      div.appendChild(dd)
      for (k in feature.object.meta) {
        dt = document.createElement('dt')
        dt.appendChild(document.createTextNode(k))
        div.appendChild(dt)

        dd = document.createElement('dd')
        dd.appendChild(document.createTextNode(feature.object.meta[k]))
        div.appendChild(dd)
      }

      displayBlock({
        dom,
        content: div,
        title: lang('header:osm_meta'),
        order: 11
      })
    }

    if (['popup'].includes(this.displayId)) {
      let id_with_sublayer = (feature.sublayer_id === 'main' ? '' : feature.sublayer_id + ':') + feature.id
      let footerContent = '<li><a class="showDetails" href="#' + this.category.id + '/' + id_with_sublayer + '/details">' + lang('show details') + '</a></li>'
      footerContent += '<li>' + editLink(feature) + '</li>'
      displayBlock({
        dom,
        content: '<ul class="footer">' + footerContent + '</ul>',
        order: 1000
      })
    }

    call_hooks_callback('show-' + this.displayId, feature, category, dom, err => {
      if (err.length) {
        return callback(err.join(', '))
      }

      callback()
    })
  }

  close () {
    if (this.updateListener) {
      this.category.off('update', this.updateListener)
    }

    call_hooks('hide-' + this.displayId)
  }
}

function getProperty(data, id, displayId, fallbackIds) {
  const idCap = id[0].toUpperCase() + id.substr(1)

  let variants = [displayId + idCap]
  variants = variants.concat(fallbackIds.map(fid => fid + idCap))
  variants.push(id)

  variants = variants.filter(vid => vid in data)

  if (variants.length) {
    return data[variants[0]]
  }

  return ''
}
