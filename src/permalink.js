const hooks = require('modulekit-hooks')

let permalink

hooks.register('state-update', function (state, hash) {
  permalink.href = hash
})

hooks.register('init', function () {
  let li = document.createElement('li')

  permalink = document.createElement('a')
  li.appendChild(permalink)
  permalink.innerHTML = lang('main:permalink')

  let menu = document.getElementById('menu')
  menu.appendChild(li)
})
