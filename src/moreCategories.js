const tabs = require('modulekit-tabs')

const Browser = require('./Browser')

let tab

function moreCategoriesIndex () {
  let content = tab.content

  content.innerHTML = '<h3>' + lang('more_categories') + '</h3>'

  const dom = document.createElement('div')
  content.appendChild(dom)

  const browser = new Browser('more-categories', dom)
  browser.buildPage({})

  browser.on('close', () => tab.unselect())
}

register_hook('init', function (callback) {
  tab = new tabs.Tab({
    id: 'moreCategories'
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i>'
  tab.header.title = lang('more_categories')

  tab.on('select', () => {
    tab.content.innerHTML = ''
    moreCategoriesIndex()
  })
})

