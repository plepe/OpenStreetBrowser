module.exports = function displayBlock ({dom, content, title}) {
  const block = document.createElement('div')
  block.className = 'block'

  if (title) {
    const header = document.createElement('h3')
    header.innerHTML = title
    block.appendChild(header)
  }

  if (typeof content === 'string') {
    const div = document.createElement('div')
    div.innerHTML = content
    block.appendChild(div)
  } else {
    block.appendChild(content)
  }

  dom.appendChild(block)
}
