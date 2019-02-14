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
    return tags
  }

  preSaveTags (tags, def) {
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
          console.log(data)
          // now save to OSM

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
