module.exports = function displayBlock ({dom, content, title, order}) {
  const block = document.createElement('div')
  block.className = 'block'

  if (order) {
    block.setAttribute('data-order', order)
  }

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

  reorder(dom)

  return block
}

function reorder (dom) {
  const children = Array.from(dom.children)
  children.sort((child1, child2) => {
    let o1 = child1.hasAttribute('data-order') ? parseFloat(child1.getAttribute('data-order')) : 0
    let o2 = child2.hasAttribute('data-order') ? parseFloat(child2.getAttribute('data-order')) : 0
    return o1 - o2
  })
  children.forEach(child => dom.appendChild(child))
}
