const modules = module.exports = []

// Render markers on the map
import markers from 'leaflet-geowiki/src/markers'
modules.push(markers)

// Add the possibility to switch the main repo
import repo from './src/repo'
modules.push(repo)
