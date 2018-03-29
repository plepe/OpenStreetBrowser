function httpGet (url, options, callback) {
  let corsRetry = true
  var xhr

  function readyStateChange () {
    if (xhr.readyState === 4) {
      if (corsRetry && xhr.status === 0) {
        corsRetry = false
        return viaServer()
      }

      if (xhr.status === 200) {
        callback(null, { body: xhr.responseText })
      } else {
        callback(xhr.responseText)
      }
    }
  }

  function direct () {
    xhr = new XMLHttpRequest()
    xhr.open('get', url, true)
    xhr.responseType = 'text'
    xhr.onreadystatechange = readyStateChange
    xhr.send()
  }

  function viaServer () {
    xhr = new XMLHttpRequest()
    xhr.open('get', 'httpGet.php?url=' + encodeURIComponent(url), true)
    xhr.responseType = 'text'
    xhr.onreadystatechange = readyStateChange
    xhr.send()
  }

  if (options.forceServerLoad) {
    viaServer()
  } else {
    direct()
  }
}

module.exports = httpGet
