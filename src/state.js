var queryString = require('query-string')

function get () {
  var state = {}

  // path
  if (currentPath) {
    state.path = currentPath
  }

  // location
  if (typeof map.getZoom() === 'undefined') {
    return
  }

  var center = map.getCenter()
  var zoom = map.getZoom()

  state.lat = center.lat
  state.lon = center.lng
  state.zoom = zoom

  // other modules
  call_hooks('state-get', state)

  // done
  return state
}

function apply (state) {
  // path
  setPath(state.path)

  // location
  if (state.lat && state.lon && state.zoom) {
    if (typeof map.getZoom() === 'undefined') {
      map.setView({ lat: state.lat, lng: state.lon }, state.zoom)
    } else {
      map.flyTo({ lat: state.lat, lng: state.lon }, state.zoom)
    }
  }

  // other modules
  call_hooks('state-apply', state)
}

function stringify (state) {
  var link = ''

  if (!state) {
    state = get()
  }

  var tmpState = JSON.parse(JSON.stringify(state))

  // path
  if (state.path) {
    link += state.path
    delete tmpState.path
  }

  // location
  var locPrecision = 5
  if (state.zoom) {
    locPrecision =
      state.zoom > 16 ? 5 :
      state.zoom >  8 ? 4 :
      state.zoom >  4 ? 3 :
      state.zoom >  2 ? 2 :
      state.zoom >  1 ? 1 : 0
  }

  if (state.zoom && state.lat && state.lon) {
    link += (link === '' ? '' : '&') + 'map=' +
      parseFloat(state.zoom).toFixed(0) + '/' +
      state.lat.toFixed(locPrecision) + '/' +
      state.lon.toFixed(locPrecision)

    delete tmpState.zoom
    delete tmpState.lat
    delete tmpState.lon
  }

  var newHash = queryString.stringify(tmpState)
  newHash = newHash.replace(/%2F/g, '/')

  if (newHash !== '') {
    link += (link === '' ? '' : '&') + newHash
  }

  return link
}

function parse (link) {
  var firstEquals = link.search('=')
  var firstAmp = link.search('&')
  var urlNonPathPart = ''
  var newState = {}
  var newPath = ''

  if (link === '') {
    // nothing
  } else if (firstEquals === -1) {
    if (firstAmp === -1) {
      newPath = link
    } else {
      newPath = link.substr(0, firstAmp)
    }
  } else {
     if (firstAmp === -1) {
      urlNonPathPart = link
    } else if (firstAmp < firstEquals) {
      newPath = link.substr(0, firstAmp)
      urlNonPathPart = link.substr(firstAmp + 1)
    } else {
      urlNonPathPart = link
    }
  }

  newState = queryString.parse(urlNonPathPart)
  if (newPath !== '') {
    newState.path = newPath
  }

  if ('map' in newState) {
    var parts = newState.map.split('/')
    newState.zoom = parts[0]
    newState.lat = parts[1]
    newState.lon = parts[2]
    delete newState.map
  }

  return newState
}

function update (state, push) {
  if (!state) {
    state = get()
  }

  var newHash = '#' + stringify(state)

  if (push) {
    history.pushState(null, null, newHash)
    console.log('push', newHash, state)
  } else if (location.hash !== newHash && (location.hash !== '' || newHash !== '#')) {

    history.replaceState(null, null, newHash)
    console.log('replace', newHash, state)
  } else {
    console.log('ignore', newHash, state)
  }
}

module.exports = {
  get: get, // get the current app state
  apply: apply, // apply a state to the current app

  stringify: stringify, // create a link from a state (or the current state)
  parse: parse, // parse a state from a link

  update: update // update url (either replace or push)
}
