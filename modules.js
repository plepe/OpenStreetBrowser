const modules = module.exports = []

// Options handling and menu
import options from './src/options'
modules.push(options)

// YAML import/export of options
import optionsYaml from './src/optionsYaml'
modules.push(optionsYaml)

// Render markers on the map
import markers from 'leaflet-geowiki/src/markers'
modules.push(markers)

// Add the possibility to switch the main repo
import repo from './src/repo'
modules.push(repo)
