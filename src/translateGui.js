const formDefUser = {
  fullname: {
    type: 'text',
    name: lang('userFullname')
  },
  langCode: {
    type: 'text',
    name: lang('langCode')
  },
  langName: {
    type: 'text',
    name: lang('langName')
  }
}

function translateGui (formDef) {
  let currentForm = new form(formDef)
  let win = wm.createWindow({ width: 500, height: 500, title: 'Test Window' })

  currentForm.show(win.content)
}

register_hook('init', function () {
  translateGui(formDefUser)
})
