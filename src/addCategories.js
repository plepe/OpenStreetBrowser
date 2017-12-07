var OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
require('./addCategories.css')

var content

function addCategoriesShow (repo) {
  if (!content) {
    content = document.createElement('div')
    content.id = 'contentAddCategories'
    document.getElementById('content').appendChild(content)
  }

  content.innerHTML = 'Loading ...'
  document.getElementById('content').className = 'addCategories'

  OpenStreetBrowserLoader.getRepo(repo, {}, function (err, repoData) {
    var ul = document.createElement('ul')

    for (var id in repoData) {
      var data = repoData[id]

      var li = document.createElement('li')

      var a = document.createElement('a')
      if (repo) {
        a.href = '#categories=' + repo + '.' + id
        a.onclick = function () {
          addCategoriesHide()
        }
      } else {
        a.href = '#'
        a.onclick = function (id) {
          addCategoriesShow(id)
          return false
        }.bind(this, id)
      }

      li.appendChild(a)
      a.appendChild(document.createTextNode('name' in data ? lang(data.name) : id))
      li.appendChild(a)

      ul.appendChild(li)
    }

    while(content.firstChild)
      content.removeChild(content.firstChild)

    content.appendChild(ul)
  })
}

function addCategoriesHide () {
  document.getElementById('content').className = 'list'
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
