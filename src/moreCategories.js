const tabs = require('modulekit-tabs')

let tab

function moreCategoriesIndex () {
  let content = tab.content

  content.innerHTML = '<h3>' + lang('more_categories') + '</h3>'

  const dom = document.createElement('div')
  content.appendChild(dom)

  hooks.call('more-categories-index', dom)
}

register_hook('init', function (callback) {
  tab = new tabs.Tab({
    id: 'moreCategories'
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i>'
  tab.header.title = lang('more_categories')

  let initialized = false

  tab.on('select', () => {
    if (!initialized) {
      moreCategoriesIndex()
      initialized = true
    }
  })
})

