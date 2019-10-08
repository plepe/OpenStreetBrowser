const formatter = {
  wikidata: value => '<a target="_blank" href="https://wikidata.org/wiki/' + encodeURIComponent(value) + '">' + value + '</a>'
}

module.exports = function tagsDisplay (tags) {
  const div = document.createElement('dl')
  div.className = 'tags'
  for (let k in tags) {
    const dt = document.createElement('dt')
    dt.appendChild(document.createTextNode(k))
    div.appendChild(dt)

    const dd = document.createElement('dd')
    if (k in formatter) {
      dd.innerHTML = formatter[k](tags[k])
    } else {
      dd.appendChild(document.createTextNode(tags[k]))
    }
    div.appendChild(dd)
  }

  return div
}
