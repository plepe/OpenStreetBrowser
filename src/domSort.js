module.exports = function (dom, attribute='weight') {
  const list = Array.from(dom.children).sort(
    (a, b) => (a.getAttribute(attribute) || 0) - (b.getAttribute(attribute) || 0)
  )

  list.forEach(el => dom.appendChild(el))
}
