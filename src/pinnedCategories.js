const tabs = require('modulekit-tabs')

register_hook('init', startup)
function startup () {
  if ('pinned-categories' in options && Array.isArray(options['pinned-categories'])) {
    options['pinned-categories'].forEach(id => {
      OpenStreetBrowserLoader.getCategory(id, {}, (err, category) => {
        if (err) {
          console.error(err)
          return global.alert('Error loading pinned category "' + id + '":\n' + err.message)
        }

        if (!category.parentDom) {
          global.rootCategories[id] = category
          category.setParentDom(document.getElementById('contentListAddCategories'))
        }
      })
    })
  }
}

function isPinned (id) {
  return 'pinned-categories' in options && Array.isArray(options['pinned-categories']) ? options['pinned-categories'].includes(id) : false
}

hooks.register('category-overpass-init', (category) => {
  const m = category.id.match(/^(.*)\/(.*)$/)
  if (!m) {
    return
  }

  const id = category.id
  category.tabPin = new tabs.Tab({
    id: 'pin',
    weight: 9
  })

  category.tools.add(category.tabPin)
  let pinHeader = document.createElement('span')
  pinHeader.href = '#'
  category.tabPin.header.appendChild(pinHeader)
  updateHeader(category, isPinned(id), pinHeader)

  category.on('editor-init', (editor) => {
    // overwrite default postApplyContent action
    editor._postApplyContent = () => {
      if (!isPinned(id) && editor.category) {
        editor.category.remove()
        editor.category = null
      } else {
        editor.category.close()
      }
    }
  })

  category.tabPin.on('select', () => {
    category.tabPin.unselect()
    let nowPinned = !isPinned(id)
    updateHeader(category, nowPinned, pinHeader)

    ajax(nowPinned ? 'options_save_key_array_add' : 'options_save_key_array_remove',
      {},
      { option: 'pinned-categories', element: id },
      result => {
        if (result.success) {
          options = result.options
        }
      }
    )
  })
})

register_hook('options_form', def => {
  def['pinned-categories'] = {
    name: lang('pinnedCategories:remembered'),
    type: 'text',
    count: {default: 1, index_type: 'array'}
  }
})

register_hook('options_save', (newOptions, oldOptions) => {
  startup(newOptions)

  if (oldOptions && Array.isArray(oldOptions['pinned-categories'])) {
    const newList = newOptions['pinned-categories'] ?? []
    oldOptions['pinned-categories'].forEach(id => {
      if (!newList.includes(id)) {
        OpenStreetBrowserLoader.getCategory(id, {}, (err, category) => {
          updateHeader(category, false, category.tabPin.header.firstChild)
        })
      }
    })
  }
})

function updateHeader (category, isPinned, pinHeader) {
  pinHeader.title = lang(isPinned ? 'pinnedCategories:forget' : 'pinnedCategories:remember')
  pinHeader.innerHTML = isPinned ? '<i class="fa-solid fa-bookmark"></i>' : '<i class="fa-regular fa-bookmark"></i>'

  if (!category.tabEdit) {
    return
  }

  if (isPinned) {
    category.tabEdit.header.innerHTML = '<i class="fa fa-clone"></i>'
    category.tabEdit.header.title = lang('pinnedCategories:clone')
  } else {
    category.tabEdit.header.innerHTML = '<i class="fa fa-pen"></i>'
    category.tabEdit.header.title = lang('edit')
  }
}
