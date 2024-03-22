const tabs = require('modulekit-tabs')

register_hook('init', () => {
  if ('custom-category-pin' in options && options['custom-category-pin']) {
    options['custom-category-pin'].forEach(id => {
      OpenStreetBrowserLoader.getCategory('custom/' + id, {}, (err, category) => {
        category.setParentDom(document.getElementById('contentListAddCategories'))
      })
    })
  }
})

hooks.register('category-overpass-init', (category) => {
  const m = category.id.match(/^custom\/(.*)$/)
  if (!m) {
    return
  }

  const id = m[1]
  category.tabPin = new tabs.Tab({
    id: 'pin',
    weight: 9
  })

  let isPinned = 'custom-category-pin' in options && Array.isArray(options['custom-category-pin']) ? options['custom-category-pin'].includes(id) : false

  category.tools.add(category.tabPin)
  let pinHeader = document.createElement('span')
  pinHeader.href = '#'
  category.tabPin.header.appendChild(pinHeader)
  updateHeader(category, isPinned, pinHeader)

  category.tabPin.on('select', () => {
    category.tabPin.unselect()
    isPinned = !isPinned
    updateHeader(category, isPinned, pinHeader)

    ajax(isPinned ? 'options_save_key_array_add' : 'options_save_key_array_remove',
      {},
      { option: 'custom-category-pin', element: id },
      result => {
        if (result.success) {
          options = result.options
        }
      }
    )
  })
})

register_hook('options_form', def => {
  def['custom-category-pin'] = {
    name: lang('customCategory:remembered'),
    type: 'text',
    count: {default: 1}
  }
})

function updateHeader (category, isPinned, pinHeader) {
  pinHeader.title = lang(isPinned ? 'customCategory:forget' : 'customCategory:remember')
  pinHeader.innerHTML = isPinned ? '<i class="fa-solid fa-bookmark"></i>' : '<i class="fa-regular fa-bookmark"></i>'
}
