const exportAll = require('./exportAll')
const tagsDisplay = require('./tagsDisplay').display

module.exports = function objectDisplay (data, category) {
  var div, h, dt, dd, li, a
  var k
  var dom = document.getElementById('contentDetails')

  dom.innerHTML = ''

  let header = document.createElement('div')
  header.className = 'header'
  dom.appendChild(header)

  div = document.createElement('div')
  div.className = 'description'
  div.innerHTML = data.data.popupDescription || data.data.description || ''
  header.appendChild(div)
  data.sublayer.updateAssets(div, data)

  div = document.createElement('div')
  div.className = 'title'
  div.innerHTML = data.data.title || ''
  header.appendChild(div)
  data.sublayer.updateAssets(div, data)

  div = document.createElement('div')
  div.className = 'body'
  dom.appendChild(div)

  function updateBody (div) {
    div.innerHTML = data.data.detailBody || data.data.body || ''
    data.sublayer.updateAssets(div, data)
  }

  data.object.on('update', updateBody.bind(this, div))
  updateBody(div)

  div = document.createElement('div')
  div.className = 'body'
  dom.appendChild(div)
  category.renderTemplate(data, 'detailsBody', function (div, err, result) {
    div.innerHTML = result
    data.sublayer.updateAssets(div, data)
  }.bind(this, div))

  call_hooks_callback('show-details', data, category, dom,
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
  exportAll(data, div)

  h = document.createElement('h3')
  h.innerHTML = lang('header:attributes')
  dom.appendChild(h)

  dom.appendChild(tagsDisplay(data.object.tags))

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
  a.appendChild(document.createTextNode(data.object.type + '/' + data.object.osm_id))
  a.href = config.urlOpenStreetMap + '/' + data.object.type + '/' + data.object.osm_id
  a.target = '_blank'

  dd.appendChild(a)
  div.appendChild(dd)
  for (k in data.object.meta) {
    dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)

    dd = document.createElement('dd')
    dd.appendChild(document.createTextNode(data.object.meta[k]))
    div.appendChild(dd)
  }
  dom.appendChild(div)
}


