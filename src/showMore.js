require('./showMore.css')

function delayedUpdate (dom, p) {
  if (!p.timer) {
    p.timer = global.setTimeout(
      () => {
        delete p.timer

        if (dom.scrollHeight > dom.offsetHeight && dom.classList.contains('collapsed')) {
          p.classList.add('active')
        }

        if (dom.scrollHeight <= dom.offsetHeight && dom.classList.contains('collapsed')) {
          p.classList.remove('active')
        }
      },
      1
    )
  }
}

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

  category.on('add', delayedUpdate.bind(this, dom, p))
  category.on('remove', delayedUpdate.bind(this, dom, p))
  category.on('open', () => {
    p.classList.remove('active')
    dom.classList.add('collapsed')
    delayedUpdate(dom, p)
  })
}

module.exports = showMore
