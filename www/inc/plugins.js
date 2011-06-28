/**
 * plugins_loaded - Checks if a plugin is loaded
 * @param string ID of plugin
 * @return boolean true if plugin has been loaded
 */
function plugins_loaded(plugin) {
  return in_array(plugin, plugins);
}
