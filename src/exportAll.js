const tabs = require('modulekit-tabs')
const async = require('async')
const FileSaver = require('file-saver')

const chunkSplit = require('./chunkSplit')

const types = {
  GeoJSON: require('./ExportGeoJSON'),
  OSMXML: require('./ExportOSMXML'),
  OSMJSON: require('./ExportOSMJSON')
}

let tab
let formExport

function prepareDownload (callback) {
  let conf = formExport.get_data()

  call_hooks('prepareDownload', conf)

  global.baseCategory.allMapFeatures((err, data) => {
    if (err) {
      return callback(err)
    }

    createDownload(conf, data, callback)
  })
}

function createDownload (conf, data, callback) {
    let type = types[conf.type]
    let exportFun = new type(conf)

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

        if (data.length !== 0) {
          data = data.reduce((all, chunk) => all.concat(chunk))
        }

        let result = exportFun.finish(data)

        var blob = new Blob([ result.content ], { type: result.fileType + ';charset=utf-8' })
        FileSaver.saveAs(blob, 'openstreetbrowser.' + result.extension)

        call_hooks('finishDownload', conf)

        callback()
      }
    )
}

function formDef () {
  let values = {}
  Object.keys(types).forEach(type =>
    values[type] = lang('export:' + type)
  )

  return {
    type: {
      name: 'Type',
      type: 'radio',
      values,
      default: Object.keys(types)[0]
    }
  }
}

register_hook('init', function () {
  tab = new tabs.Tab({
    id: 'export',
    weight: 10
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-download" aria-hidden="true"></i>'
  tab.header.title = lang('export-all')
  tab.content.innerHTML = '<h3>' + lang('export-all') + '</h3>'

  formExport = new form('export', formDef())

  let domForm = document.createElement('form')
  tab.content.appendChild(domForm)
  formExport.show(domForm)

  let submit = document.createElement('input')
  submit.type = 'submit'
  submit.value = lang('export-prepare')
  submit.onclick = () => {
    let progressIndicator = document.createElement('div')
    progressIndicator.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')
    tab.content.appendChild(progressIndicator)
    submit.style.display = 'none'

    prepareDownload((err) => {
      if (err) {
        alert(err)
      }

      submit.style.display = 'block'
      tab.content.removeChild(progressIndicator)
      tab.unselect()
    })
  }
  tab.content.appendChild(submit)

  tab.on('select', () => {
    formExport.resize()
  })
})

module.exports = (data) => {
  const div = document.createElement('div')

  let formExport = new form('exportOne', formDef())

  let domForm = document.createElement('form')
  div.appendChild(domForm)
  formExport.show(domForm)

  let submit = document.createElement('input')
  submit.type = 'submit'
  submit.value = lang('export-prepare')
  submit.onclick = () => {
    let progressIndicator = document.createElement('div')
    progressIndicator.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')
    div.appendChild(progressIndicator)
    submit.style.display = 'none'

    let conf = formExport.get_data()

    conf.singleFeature = true

    createDownload(conf, [ data ], (err) => {
      if (err) {
        alert(err)
      }

      submit.style.display = 'block'
      div.removeChild(progressIndicator)
    })
  }
  div.appendChild(submit)

  global.setTimeout(() => formExport.resize(), 0)

  return div
}
