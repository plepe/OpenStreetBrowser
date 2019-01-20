module.exports = function getPathFromJSON (path, json) {
  if (typeof path === 'string') {
    path = path.split(/\./)
  }

  if (path.length === 0) {
    return json
  }

  return getPathFromJSON(path.slice(1), json[path[0]])
}
