let win

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
  const wm = require('./windowManager')

  let currentForm = new form(formDef)
  win = wm.createWindow({ width: 500, height: 500, title: 'Test Window' })

  currentForm.show(win.content)

  win.open()
wm.overlay.style.zIndex = 1000
}

register_hook('init', () => {
  window.setTimeout(
    () => {
      translateGui(formDefUser)
    },
    2000
  )
})
