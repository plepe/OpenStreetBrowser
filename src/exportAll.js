const tabs = require('modulekit-tabs')
const async = require('async')
const FileSaver = require('file-saver')

const chunkSplit = require('./chunkSplit')

const types = {
  GeoJSON: require('./ExportGeoJSON'),
  OSMXML: require('./ExportOSMXML')
}

let tab
let formExport

function prepareDownload (callback) {
  let conf = formExport.get_data()
  let fileType
  let extension
  let type = types[conf.type]
  let exportFun = new type(conf)

  global.baseCategory.allMapFeatures((err, data) => {
    let chunks = chunkSplit(data, 1000)
    let parentNode

    async.mapLimit(
      chunks,
      1,
      (chunk, done) => {
        async.map(chunk,
          (ob, done) => exportFun.each(ob, done),
          (err, result) => {
            global.setTimeout(() => done(err, result), 0)
          }
        )
      },
      (err, data) => {
        if (err) {
          return callback(err)
        }

        data = data.reduce((all, chunk) => all.concat(chunk))

        let result = exportFun.finish(data)

        var blob = new Blob([ result.content ], { type: result.fileType + ';charset=utf-8' })
        FileSaver.saveAs(blob, 'openstreetbrowser.' + result.extension)

        callback()
      }
    )
  })
}

register_hook('init', function () {
  tab = new tabs.Tab({
    id: 'export'
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-download" aria-hidden="true"></i>'
  tab.content.innerHTML = lang('export-all')

  let values = {}
  Object.keys(types).forEach(type =>
    values[type] = lang('export:' + type)
  )

  formExport = new form('export', {
    type: {
      name: 'Type',
      type: 'radio',
      values,
      default: Object.keys(types)[0]
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
    prepareDownload((err) => {
      if (err) {
        alert(err)
      }

      submit.removeAttribute('disabled')
      tab.unselect()
    })
  }
  tab.content.appendChild(submit)

  tab.on('select', () => {
    formExport.resize()
  })
})
