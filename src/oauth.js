require('./oauth.css')

function oauth (options, callback) {
  let iframe = document.createElement('iframe')
  iframe.className = 'oauth'
  iframe.src = 'oauth.php'
  document.body.appendChild(iframe)
}

module.exports = oauth
