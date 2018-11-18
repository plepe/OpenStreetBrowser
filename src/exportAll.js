const tabs = require('modulekit-tabs')
const async = require('async')
const chunkSplit = require('./chunkSplit')

let tab
let formExport

function prepareDownload (callback) {
  let conf = formExport.get_data()
  let result = []

  global.baseCategory.allMapFeatures((err, data) => {
    console.log(data)

    let chunks = chunkSplit(data, 1000)

    async.eachLimit(
      chunks,
      1,
      (chunk, done) => {
        result = result.concat(chunk.map(ob => {
          switch (conf.type) {
            case 'geojson':
              return ob.object.GeoJSON(conf)
          }
        }))

        global.setTimeout(done, 0)
      },
      (err) => {
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
        }

        console.log(result)

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
    })
  }
  tab.content.appendChild(submit)

  tab.on('select', () => {
    formExport.resize()
  })
})
