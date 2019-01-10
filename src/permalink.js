let permalink

register_hook('state-update', function (state, hash) {
  permalink.href = hash
})

register_hook('init', function () {
  let li = document.createElement('li')

  permalink = document.createElement('a')
  li.appendChild(permalink)
  permalink.innerHTML = 'Permalink'

  let menu = document.getElementById('menu')
  menu.appendChild(li)
})
