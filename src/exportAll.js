const tabs = require('modulekit-tabs')
const async = require('async')
const FileSaver = require('file-saver')

const chunkSplit = require('./chunkSplit')

let tab
let formExport

function prepareDownload (callback) {
  let conf = formExport.get_data()
  let fileType
  let extension

  global.baseCategory.allMapFeatures((err, data) => {
    let chunks = chunkSplit(data, 1000)

    async.mapLimit(
      chunks,
      1,
      (chunk, done) => {
        async.map(chunk,
          (ob, done) => {
            switch (conf.type) {
              case 'geojson':
                done(null, ob.object.GeoJSON(conf))
                break
              default:
                done('wrong type')
            }
          },
          (err, result) => {
            global.setTimeout(() => done(err, result), 0)
          }
        )
      },
      (err, result) => {
        if (err) {
          return callback(err)
        }

        switch (conf.type) {
          case 'geojson':
            result = {
              type: 'FeatureCollection',
              features: result
            }
            result = JSON.stringify(result, null, '    ')
            fileType = 'application/json'
            extension = 'geojson'
            break
        }

        var blob = new Blob([ result ], { type: fileType + ';charset=utf-8' })
        FileSaver.saveAs(blob, 'openstreetbrowser.' + extension)

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
