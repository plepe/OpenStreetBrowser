module.exports = function stripLinks (dom) {
  var as = dom.getElementsByTagName('a')
  as = Array.prototype.slice.call(as)

  as.forEach(function (current) {
    while (current.firstChild) {
      current.parentNode.insertBefore(current.firstChild, current)
    }

    current.parentNode.removeChild(current)
  })
}
