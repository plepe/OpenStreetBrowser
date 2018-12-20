require('./showMore.css')

function showMore (category, dom) {
  dom.classList.add('collapsed')

  let p = document.createElement('div')
  p.className = 'showMore'
  dom.parentNode.insertBefore(p, dom.nextSibling)

  let a = document.createElement('a')
  a.href = '#'
  a.innerHTML = lang('more_results')
  a.onclick = () => {
    dom.classList.remove('collapsed')
    p.classList.remove('active')
    return false
  }
  p.appendChild(a)

  category.on('add', () => {
    if (dom.scrollHeight > dom.offsetHeight && dom.classList.contains('collapsed')) {
      p.classList.add('active')
    }
  })
  category.on('remove', () => {
    if (dom.scrollHeight <= dom.offsetHeight && dom.classList.contains('collapsed')) {
      p.classList.remove('active')
    }
  })
  category.on('open', () => {
    p.classList.remove('active')
    dom.classList.add('collapsed')
  })
}

module.exports = showMore
