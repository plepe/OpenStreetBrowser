/* global OverpassLayer, repositoriesGitea */
require('./addCategories.css')

const tabs = require('modulekit-tabs')
const weightSort = require('weight-sort')

const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')
const customCategory = require('./customCategory')

let tab

function addCategoriesList (options = {}) {
  let content = tab.content

  content.innerHTML = '<h3>' + lang('more_categories') + '</h3>' + '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  OpenStreetBrowserLoader.getRepositoryList(options, function (err, repoData) {
    if (err) {
      return global.alert(err)
    }

    content.innerHTML = '<h3>' + lang('more_categories') + '</h3>'

    var categoryUrl = null
    if (repoData.categoryUrl) {
      categoryUrl = OverpassLayer.twig.twig({ data: repoData.categoryUrl, autoescape: true })
    }

    var list = {}

    customCategory(content)

    if (typeof repositoriesGitea === 'object' && repositoriesGitea.url) {
      let a = document.createElement('a')
      a.href = repositoriesGitea.url
      a.target = '_blank'
      a.innerHTML = lang('more_categories_gitea')
      content.appendChild(a)
    }

    list = weightSort(repoData, {
      key: 'timestamp',
      reverse: true
    })

    let menu = document.createElement('ul')
    menu.className = 'menu'
    content.appendChild(menu)

    let header = document.createElement('h3')
    header.innerHTML = lang('repositories') + ':'
    content.appendChild(header)

    var ul = document.createElement('ul')

    for (var id in list) {
      var data = list[id]

      var repositoryUrl = null
      if (data.repositoryUrl) {
        repositoryUrl = OverpassLayer.twig.twig({ data: data.repositoryUrl, autoescape: true })
      }

      var li = document.createElement('li')

      let a = document.createElement('a')
      a.href = '#'
      a.onclick = function (id) {
        addCategoriesShow(id)
        return false
      }.bind(this, id)

      li.appendChild(a)
      a.appendChild(document.createTextNode('name' in data ? lang(data.name) : id))

      var editLink = null
      if (repositoryUrl) {
        editLink = document.createElement('a')
        editLink.href = repositoryUrl.render({ repositoryId: id })
      }
      if (editLink) {
        editLink.className = 'source-code'
        editLink.title = 'Show source code'
        editLink.target = '_blank'
        editLink.innerHTML = '<i class="fa fa-file-code-o" aria-hidden="true"></i>'
        li.appendChild(document.createTextNode(' '))
        li.appendChild(editLink)
      }

      ul.appendChild(li)
    }

    content.appendChild(ul)
  })
}

function addCategoriesShow (repo, options={}) {
  let content = tab.content

  let [ repoId, branchId ] = repo.split(/~/)

  if (!branchId) {
    branchId = 'master'
  }

  content.innerHTML = '<h3>' + lang('more_categories') + '</h3>' + '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  OpenStreetBrowserLoader.getRepository(repo, options, function (err, repository) {
    if (err) {
      return global.alert(err)
    }

    const repoData = repository.data

    content.innerHTML = '<h3>' + lang('more_categories') + '</h3>'

    var categoryUrl = null
    if (repoData.categoryUrl) {
      categoryUrl = OverpassLayer.twig.twig({ data: repoData.categoryUrl, autoescape: true })
    }

    var list = {}

    customCategory(content)

    var backLink = document.createElement('a')
    backLink.className = 'back'
    backLink.href = '#'
    backLink.innerHTML = '<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> '
    backLink.appendChild(document.createTextNode(lang('back')))

    backLink.onclick = function () {
      addCategoriesList()
      return false
    }
    content.appendChild(backLink)

    let h = document.createElement('h2')
    h.appendChild(document.createTextNode(repoId))
    content.appendChild(h)

    list = repoData.categories

    let menu = document.createElement('ul')
    menu.className = 'menu'
    content.appendChild(menu)

    let li = document.createElement('li')
    menu.appendChild(li)

    let text = document.createElement('a')
    text.innerHTML = lang('repo-use-as-base')
    text.href = '#repo=' + repo
    text.onclick = addCategoriesHide
    li.appendChild(text)

    li = document.createElement('li')
    menu.appendChild(li)

    text = document.createElement('a')
    text.innerHTML = lang('reload')
    text.href = '#'
    text.onclick = () => {
      addCategoriesShow(repo, { force: true })
    }
    li.appendChild(text)

    if ('branches' in repoData) {
      let li = document.createElement('li')
      menu.appendChild(li)

      let text = document.createElement('span')
      text.innerHTML = lang('available_branches') + ': '
      li.appendChild(text)

      let branchSelector = document.createElement('select')

      branchSelector.onchange = () => {
        let branch = branchSelector.value

        addCategoriesShow(repoId + '~' + branch)
      }

      Object.keys(repoData.branches).forEach(
        branch => {
          let option = document.createElement('option')
          option.value = branch
          option.appendChild(document.createTextNode(branch))

          if (repoData.branch === branch) {
            option.selected = true
          }

          branchSelector.appendChild(option)
        }
      )
      li.appendChild(branchSelector)
    }

    let header = document.createElement('h3')
    header.innerHTML = lang('categories') + ':'
    content.appendChild(header)

    var ul = document.createElement('ul')

    for (var id in list) {
      var data = list[id]

      var repositoryUrl = null
      if (data.repositoryUrl) {
        repositoryUrl = OverpassLayer.twig.twig({ data: data.repositoryUrl, autoescape: true })
      }

      li = document.createElement('li')

      let a = document.createElement('a')
      a.href = '#categories=' + (repo === 'default' ? '' : repo + '/') + id
      a.onclick = function () {
        addCategoriesHide()
      }

      li.appendChild(a)
      a.appendChild(document.createTextNode('name' in data ? lang(data.name) : id))

      var editLink = null
      if (repo && categoryUrl) {
        editLink = document.createElement('a')
        editLink.href = categoryUrl.render({ repositoryId: repoId, categoryId: id, branchId: branchId })
      }
      if (!repo && repositoryUrl) {
        editLink = document.createElement('a')
        editLink.href = repositoryUrl.render({ repositoryId: id })
      }
      if (editLink) {
        editLink.className = 'source-code'
        editLink.title = 'Show source code'
        editLink.target = '_blank'
        editLink.innerHTML = '<i class="fa fa-file-code-o" aria-hidden="true"></i>'
        li.appendChild(document.createTextNode(' '))
        li.appendChild(editLink)
      }

      ul.appendChild(li)
    }

    content.appendChild(ul)
  })
}

function addCategoriesHide () {
  tab.unselect()
}

register_hook('init', function (callback) {
  tab = new tabs.Tab({
    id: 'addCategories'
  })
  global.tabs.add(tab)

  tab.header.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i>'
  tab.header.title = lang('more_categories')

  let initialized = false

  tab.on('select', () => {
    if (!initialized) {
      addCategoriesList()
      initialized = true
    }
  })
})
