function httpGet (url, options, callback) {
  var xhr = new XMLHttpRequest()
  xhr.open('get', url, true)
  xhr.responseType = 'text'
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        callback(null, { body: xhr.responseText })
      } else {
        callback(xhr.responseText)
      }
    }
  }
  xhr.send()
}

module.exports = httpGet
