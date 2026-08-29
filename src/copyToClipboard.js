module.exports = function copyToClipboard (text, dom, callback) {
  navigator.clipboard.writeText(text)
  dom.innerHTML = lang('copied-clipboard')
  global.setTimeout(() => {
    if (callback) { callback(null) }
  }, 1000)
}
