var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
require('./addCategories.css')

var content
var template = `
{% for id, data in repoData %}
{{ id }}<br/>
{% endfor %}
`

function addCategoriesShow () {
  if (!content) {
    content = document.createElement('div')
    content.id = 'contentAddCategories'
    document.getElementById('content').appendChild(content)

    template = OverpassLayer.twig.twig({ data: template, autoescape: true })
  }

  content.innerHTML = 'Loading ...'
  document.getElementById('content').className = 'addCategories'

  OpenStreetBrowserLoader.getRepo('default', {}, function (err, repoData) {
    content.innerHTML = template.render({ repoData: repoData })
  })
}

register_hook('init', function (callback) {
  var link = document.createElement('a')
  link.className = 'addCategories'
  link.href = '#'
  link.onclick = function () {
    addCategoriesShow()
    return false
  }
  link.innerHTML = lang('more_categories')

  document.getElementById('contentList').appendChild(link)
})
