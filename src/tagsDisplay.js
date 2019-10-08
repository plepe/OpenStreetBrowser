module.exports = function tagsDisplay (tags) {
  const div = document.createElement('dl')
  div.className = 'tags'
  for (let k in tags) {
    const dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)

    const dd = document.createElement('dd')
    dd.appendChild(document.createTextNode(tags[k]))
    div.appendChild(dd)
  }

  return div
}
