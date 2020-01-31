module.exports = function editLink (object) {
  switch (global.options.editor) {
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
      'id': lang('editor:id')
    },
    'default': 'id',
    'weight': 5
  }
})
