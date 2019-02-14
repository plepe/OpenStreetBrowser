const OverpassLayer = require('overpass-layer')

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

  openEditor (feature) {
    let object = feature.object
    console.log(object.id, object.tags)
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.feature.obligatoryTags || category.data.feature.recommendedTags) {
    new CategoryOverpassTagEditor(category)
  }
})
