module.exports = function loadingIndicator (dom) {
  var l = document.createElement('div')
  l.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>'
  l.className = 'loadingIndicator'
  dom.appendChild(l)
  dom.classList.add('loading')

  return {end: () => {
    dom.classList.remove('loading')
    dom.removeChild(l)
  }}
}
