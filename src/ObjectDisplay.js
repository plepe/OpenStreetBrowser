const exportAll = require('./exportAll')
const tagsDisplay = require('./tagsDisplay').display
const displayBlock = require('./displayBlock')

module.exports = class ObjectDisplay {
  constructor ({feature, category, dom, displayId, fallbackIds}, callback) {
    this.category = category

    if (!fallbackIds) {
      fallbackIds = []
    }

    var div, h, dt, dd, li, a
    var k

    dom.innerHTML = ''

    let header = document.createElement('div')
    header.className = 'header'
    dom.appendChild(header)

    div = document.createElement('div')
    div.className = 'description'
    div.innerHTML = getProperty(feature.data, 'description', displayId, fallbackIds)

    header.appendChild(div)
    feature.sublayer.updateAssets(div, feature)

    div = document.createElement('div')
    div.className = 'title'
    div.innerHTML = getProperty(feature.data, 'title', displayId, fallbackIds)
    header.appendChild(div)
    feature.sublayer.updateAssets(div, feature)

    div = document.createElement('div')
    div.className = 'body'
    dom.appendChild(div)

    function updateBody (div) {
      div.innerHTML = getProperty(feature.data, 'body', displayId, fallbackIds)
      feature.sublayer.updateAssets(div, feature)
    }

    feature.object.on('update', updateBody.bind(this, div))
    updateBody(div)

    let body = document.createElement('div')
    body.className = 'body'
    dom.appendChild(body)
    category.renderTemplate(feature, displayId + 'Body', (err, result) => {
      body.innerHTML = result
      feature.sublayer.updateAssets(body, feature)
    })

    category.on('update', this.updateListener = () => {
      category.renderTemplate(feature, displayId + 'Body', (err, result) => {
        body.innerHTML = result
        feature.sublayer.updateAssets(body, feature)
      })
    })
    
    displayBlock({
      dom,
      content: exportAll(feature),
      title: lang('header:export'),
      order: 5
    })

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

    call_hooks_callback('show-' + displayId, feature, category, dom, err => {
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
