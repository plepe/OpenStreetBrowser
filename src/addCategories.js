/* global OverpassLayer, repositoriesGitea */
require('./addCategories.css')

const tabs = require('modulekit-tabs')
const weightSort = require('weight-sort')

const state = require('./state')
const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

let tab

function addCategoriesList (content, browser, options = {}) {
  content.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  OpenStreetBrowserLoader.getRepositoryList(options, function (err, repoData) {
    if (err) {
      return global.alert(err)
    }

    var categoryUrl = null
    if (repoData.categoryUrl) {
      categoryUrl = OverpassLayer.twig.twig({ data: repoData.categoryUrl, autoescape: true })
    }

    var list = {}

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

    while (content.lastChild) {
      content.removeChild(content.lastChild)
    }

    var ul = document.createElement('ul')

    for (var id in list) {
      var data = list[id]

      var repositoryUrl = null
      if (data.repositoryUrl) {
        repositoryUrl = OverpassLayer.twig.twig({ data: data.repositoryUrl, autoescape: true })
      }

      var li = document.createElement('li')

      let a = document.createElement('a')
      a.href = '#more-categories?id=' + id

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
    browser.catchLinks()
  })
}

function addCategoriesShow (repo, browser, options={}) {
  const content = browser.dom

  let [ repoId, branchId ] = repo.split(/~/)

  if (!branchId) {
    branchId = 'master'
  }

  content.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  OpenStreetBrowserLoader.getRepository(repo, options, function (err, repository) {
    if (err) {
      return global.alert(err)
    }

    const repoData = repository.data

    content.innerHTML = ''

    var categoryUrl = null
    if (repoData.categoryUrl) {
      categoryUrl = OverpassLayer.twig.twig({ data: repoData.categoryUrl, autoescape: true })
    }

    var list = {}

    var backLink = document.createElement('a')
    backLink.className = 'back'
    backLink.href = '#more-categories?'
    backLink.innerHTML = '<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> '
    backLink.appendChild(document.createTextNode(lang('back')))
    content.appendChild(backLink)
    browser.catchLinks()

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
    text.onclick = () => browser.close()
    li.appendChild(text)

    li = document.createElement('li')
    menu.appendChild(li)

    text = document.createElement('a')
    text.innerHTML = lang('reload')
    text.href = '#more-categories?id=' + repo + '&force=true'
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

        addCategoriesShow(repoId + '~' + branch, browser, options)
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
      a.onclick = () => browser.close()

      li.appendChild(a)
      a.appendChild(document.createTextNode('name' in data ? lang(data.name) : id))

      var editLink = null
      if (repo && categoryUrl) {
        editLink = document.createElement('a')
        editLink.href = categoryUrl.render({ repositoryId: repoId, categoryId: id, branchId: branchId, categoryFormat: data.format })
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
    browser.catchLinks()
  })
}

hooks.register('browser-more-categories', (browser, parameters) => {
  const content = browser.dom

  if (!Object.keys(parameters).length) {
    let block = document.createElement('div')
    block.setAttribute('weight', 1)
    content.appendChild(block)

    let header = document.createElement('h4')
    header.innerHTML = lang('repositories')
    block.appendChild(header)

    let div = document.createElement('div')
    block.appendChild(div)
    addCategoriesList(div, browser, parameters)

    browser.catchLinks()
  }
  else if (parameters.id) {
    addCategoriesShow(parameters.id, browser, parameters)
  }
  else if (parameters.repo || parameters.categories) {
    state.apply(parameters)
    browser.close()
  }
})
