const tabs = require('modulekit-tabs')

let tab
let formExport

function prepareDownload () {
}

register_hook('init', function () {
  tab = new tabs.Tab({
    id: 'export'
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-download" aria-hidden="true"></i>'
  tab.content.innerHTML = lang('export-all')

  formExport = new form('export', {
    type: {
      name: 'Type',
      type: 'radio',
      values: {
        geojson: lang('download:geojson')
      },
      default: 'geojson'
    }
  })

  let domForm = document.createElement('form')
  tab.content.appendChild(domForm)
  formExport.show(domForm)

  let submit = document.createElement('input')
  submit.type = 'submit'
  submit.value = lang('export-prepare')
  submit.onclick = () => {
    submit.setAttribute('disabled', 'disabled')
    alert('Download')
    submit.removeAttribute('disabled')
  }
  tab.content.appendChild(submit)

  tab.on('select', () => {
    formExport.resize()
  })
})
