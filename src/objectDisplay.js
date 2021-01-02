const exportAll = require('./exportAll')
const tagsDisplay = require('./tagsDisplay').display

module.exports = function objectDisplay ({feature, category, dom}) {
  var div, h, dt, dd, li, a
  var k

  dom.innerHTML = ''

  let header = document.createElement('div')
  header.className = 'header'
  dom.appendChild(header)

  div = document.createElement('div')
  div.className = 'description'
  div.innerHTML = feature.data.popupDescription || feature.data.description || ''
  header.appendChild(div)
  feature.sublayer.updateAssets(div, feature)

  div = document.createElement('div')
  div.className = 'title'
  div.innerHTML = feature.data.title || ''
  header.appendChild(div)
  feature.sublayer.updateAssets(div, feature)

  div = document.createElement('div')
  div.className = 'body'
  dom.appendChild(div)

  function updateBody (div) {
    div.innerHTML = feature.data.detailBody || feature.data.body || ''
    feature.sublayer.updateAssets(div, feature)
  }

  feature.object.on('update', updateBody.bind(this, div))
  updateBody(div)

  div = document.createElement('div')
  div.className = 'body'
  dom.appendChild(div)
  category.renderTemplate(feature, 'detailsBody', function (div, err, result) {
    div.innerHTML = result
    feature.sublayer.updateAssets(div, feature)
  }.bind(this, div))

  call_hooks_callback('show-details', feature, category, dom,
    function (err) {
      if (err.length) {
        console.log('show-details produced errors:', err)
      }
    }
  )

  h = document.createElement('h3')
  h.innerHTML = lang('header:export')
  dom.appendChild(h)

  div = document.createElement('div')
  dom.appendChild(div)
  exportAll(feature, div)

  h = document.createElement('h3')
  h.innerHTML = lang('header:attributes')
  dom.appendChild(h)

  dom.appendChild(tagsDisplay(feature.object.tags))

  h = document.createElement('h3')
  h.innerHTML = lang('header:osm_meta')
  dom.appendChild(h)

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
  dom.appendChild(div)
}


