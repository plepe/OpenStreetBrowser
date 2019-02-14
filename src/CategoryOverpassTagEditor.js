const OverpassLayer = require('overpass-layer')
require('./CategoryOverpassTagEditor.css')

let tagEditorData = null

register_hook('init_callback', function (initState, callback) {
  ajax('CategoryOverpassTagEditorData', {},
    (result) => {
      tagEditorData = result
      callback()
    }
  )
})

class CategoryOverpassTagEditor {
  constructor (master) {
    this.master = master

    this.master.on('popup-footer', (footer, feature) => {
      let li = document.createElement('li')
      let a = document.createElement('a')
      a.href = '#'
      a.innerHTML = lang('edit')
      a.onclick = () => {
        this.openEditor(feature)
        return false
      }

      li.appendChild(a)
      footer.appendChild(li)
    })
  }

  createForm (tags, callback) {
    let def = {}

    tags.forEach(tag => {
      def[tag] = tagEditorData[tag] || { type: 'text' }
      def[tag].name = tag
    })

    callback(null, def)
  }

  postLoadTags (tags, def) {
    let data = {}

    for (let k in def) {
      let d = def[k]

      switch (d.tag_type) {
        case 'prefix_yes':
          data[k] = []
          for (let t in tags) {
            if (t.substr(0, k.length) == k && tags[t] === 'yes') {
              data[k].push(t.substr(k.length))
            }
          }
          break
        default:
          data[k] = tags[k] || null
      }
    }

    return data
  }

  preSaveTags (data, def) {
    let tags = {}

    for (let k in def) {
      let d = def[k]

      switch (d.tag_type) {
        case 'prefix_yes':
          for (let i = 0; i < d.values.length; i++) {
            tags[k + d.values[i]] = data[k].includes(d.values[i]) ? 'yes': null
          }
          break
        default:
          tags[k] = data[k] || null
      }
    }

    return tags
  }

  openEditor (feature) {
    let object = feature.object

    let editTags = []
    if (feature.data.obligatoryTags) {
      editTags = editTags.concat(feature.data.obligatoryTags.split('\n'))
    }
    if (feature.data.recommendedTags) {
      editTags = editTags.concat(feature.data.recommendedTags.split('\n'))
    }
    editTags = editTags.filter(t => t !== '')

    this.createForm(editTags, (err, def) => {
      let tagEditorForm = new form('tag-editor', def)
      tagEditorForm.set_data(this.postLoadTags(object.tags, def))

      let tagEditorDom = document.createElement('form')
      tagEditorDom.className = 'tagEditor'
      document.body.appendChild(tagEditorDom)

      tagEditorForm.show(tagEditorDom)

      let submit = document.createElement('input')
      submit.type = 'submit'
      submit.value = lang('save')
      tagEditorDom.appendChild(submit)

      tagEditorDom.addEventListener('submit',
        (event) => {
          let data = tagEditorForm.get_data()
          data = this.preSaveTags(data, def)

          for (let k in data) {
            if (data[k] === null) {
              delete object.tags[k]
            } else {
              object.tags[k] = data[k]
            }
          }

          feature.sublayer.scheduleReprocess(object.id)

          // now save to OSM
          console.log(data)

          document.body.removeChild(tagEditorDom)
          event.preventDefault()
        }
      )
    })
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.feature.obligatoryTags || category.data.feature.recommendedTags) {
    new CategoryOverpassTagEditor(category)
  }
})
