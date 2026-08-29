module.exports = function copyToClipboard (text, dom, callback) {
  navigator.clipboard.writeText(text)

  const pos = dom.getBoundingClientRect()

  const popup = document.createElement('div')
  popup.className = 'copy-to-clipboard'
  popup.style.top = pos.top + 'px'
  popup.style.left = pos.left + 'px'
  document.body.appendChild(popup)

  popup.innerHTML = lang('copied-clipboard')

  if (popup.offsetLeft + popup.offsetWidth > window.innerWidth) {
    popup.style.left = (window.innerWidth - popup.offsetWidth - 2) + 'px'
  }

  if (popup.offsetTop + popup.offsetHeight > window.innerHeight) {
    popup.style.top = (window.innerHeight - popup.offsetHeight - 2) + 'px'
  }

  global.setTimeout(() => {
    document.body.removeChild(popup)
    if (callback) { callback(null) }
  }, 1000)
}
