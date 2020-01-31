window.editLinkRemote = function (type, osm_id) {
  let bounds = global.map.getBounds()

  xhr = new XMLHttpRequest()
  let url = 'http://127.0.0.1:8111/load_and_zoom' +
    '?left=' + bounds.getWest().toFixed(5) +
    '&right=' + bounds.getEast().toFixed(5) +
    '&top=' + bounds.getNorth().toFixed(5) +
    '&bottom=' + bounds.getSouth().toFixed(5) +
    '&' + type + '=' + osm_id
  xhr.open('get', url, true)
  xhr.responseType = 'text'
  xhr.send()

  return false
}

module.exports = function editLink (object) {
  switch (global.options.editor) {
    case 'remote':
      return '<a class="editLink" href="#" onclick="return editLinkRemote(\'' + object.object.type + '\', ' + object.object.osm_id + ')">' + lang('edit') + '</a>'
    case 'id':
    default:
      return '<a target="_blank" class="editLink" href="' + global.config.urlOpenStreetMap + '/edit?editor=id&' + object.object.type + '=' + object.object.osm_id + '">' + lang('edit') + '</a>'
  }
}

register_hook('options_form', function (def) {
  def.editor = {
    'name': lang('options:chooseEditor'),
    'type': 'select',
    'values': {
      'id': lang('editor:id'),
      'remote': lang('editor:remote')
    },
    'default': 'id',
    'weight': 5
  }
})
