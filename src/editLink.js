module.exports = function editLink (object) {
  return '<a target="_blank" class="editLink" href="' + global.config.urlOpenStreetMap + '/edit?editor=id&' + object.object.type + '=' + object.object.osm_id + '">' + lang('edit') + '</a>'
}
