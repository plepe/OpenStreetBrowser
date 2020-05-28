const httpGet = require('./httpGet')
const formatter = require('./tagsDisplay').formatter

let tag2link

register_hook('init_callback', (initState, callback) => {
  httpGet('dist/tag2link.json', {}, (err, result) => {
    if (err) {
      console.error('Can\'t read dist/tag2link.json - execute bin/download_dependencies')
      return callback()
    }

    tag2link = JSON.parse(result.body)

    Object.keys(tag2link).forEach(key => {
      let tag = tag2link[key]
      let link = tag.formatter[0].link.replace('$1', '{{ value }}')

      if (tag.formatter.length > 1) {
        link = "#\" onclick=\"return tag2link(this, " + JSON.stringify(key).replace(/"/g, '&quot;') + ", {{ value|json_encode }})"
      }

      formatter.push({
        regexp: new RegExp("^" + key + "$"),
        link
      })
    })

    callback()
  })
})

global.tag2link = function (dom, key, value) {
  let div = document.createElement('div')
  div.className = 'tag2link'
  dom.parentNode.appendChild(div)

  let closeButton = document.createElement('div')
  closeButton.className = 'closeButton'
  closeButton.innerHTML = '❌'
  closeButton.onclick = () => {
    dom.parentNode.removeChild(div)
  }
  div.appendChild(closeButton)

  let selector = document.createElement('ul')
  div.appendChild(selector)

  let tag = tag2link[key]
  tag.formatter.forEach(formatter => {
    let li = document.createElement('li')

    let a = document.createElement('a')
    a.target = '_blank'
    a.href = formatter.link.replace('$1', value)
    a.appendChild(document.createTextNode(formatter.operator))

    li.appendChild(a)
    selector.appendChild(li)
  })

  return false
}
