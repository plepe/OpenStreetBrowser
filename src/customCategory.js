const tabs = require('modulekit-tabs')
const yaml = require('js-yaml')
const md5 = require('md5')
const OverpassLayer = require('overpass-layer')
const OverpassFrontendFilter = require('overpass-frontend/src/Filter')
const jsonMultilineStrings = require('json-multiline-strings')

const Window = require('./Window')
const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

const editors = []

class CustomCategoryRepository {
  constructor () {
    this.clearCache()
  }

  load (callback) {
    callback(null)
  }

  clearCache () {
    this.cache = {}
  }

  listCategories (options, callback) {
    fetch('customCategory.php?action=list')
      .then(res => res.json())
      .then(result => callback(null, result))
  }

  getCategory (id, options, callback) {
    if (id in this.cache) {
      const data = this.parseCategory(id, this.cache[id])
      return callback(null, data, this.cache[id])
    }

    fetch('customCategory.php?id=' + id)
      .then(res => res.text())
      .then(content => {
        this.cache[id] = content

        const data = this.parseCategory(id, content)

        callback(null, data, content)
      })
  }

  parseCategory (id, content) {
    let data

    try {
      data = yaml.load(content)
    } catch (e) {
      return global.alert(e)
    }

    if (data && typeof data !== 'object') {
      return new Error('Data can not be parsed into an object')
    }

    if (!data.name) {
      data.name = 'Custom ' + id.substr(0, 6)
    }

    return data
  }

  saveCategory (body, options, callback) {
    const id = md5(body)
    this.cache[id] = body

    fetch('customCategory.php?action=save', {
      method: 'POST',
      body
    })
  }

  getTemplate (id, options, callback) {
    callback(null, '')
  }
}

class CustomCategoryEditor {
  constructor (repository) {
    this.repository = repository
    editors.push(this)
  }

  load (id, callback) {
    this.repository.getCategory(id, {},
      (err, category, content) => {
        this.content = content
        callback(err, content)
      })
  }

  edit () {
    if (this.window) {
      this.window.focused = true
      return
    }

    this.window = new Window({
      title: 'Custom Category'
    })

    this.window.on('close', () => {
      this.window = null
    })

    this.textarea = document.createElement('textarea')
    this.textarea.spellcheck = false
    this.window.content.appendChild(this.textarea)
    if (this.content !== undefined) {
      this.textarea.value = this.content
    }

    const controls = document.createElement('div')
    controls.className = 'controls'
    this.window.content.appendChild(controls)

    const input = document.createElement('input')
    input.type = 'submit'
    input.value = lang('apply-keep')
    controls.appendChild(input)

    const inputClose = document.createElement('input')
    inputClose.type = 'button'
    inputClose.value = lang('apply-close')
    inputClose.onclick = () => {
      if (this.submit()) {
        this.window.close()
      }
    }
    controls.appendChild(inputClose)

    const icons = document.createElement('div')
    icons.className = 'actions'
    controls.appendChild(icons)

    this.inputDownload = document.createElement('a')
    this.textarea.onchange = () => this.updateDownload()
    this.updateDownload()
    this.inputDownload.title = lang('download')
    this.inputDownload.innerHTML = '<i class="fas fa-download"></i>'
    icons.appendChild(this.inputDownload)

    const tutorial = document.createElement('span')
    tutorial.className = 'tip-tutorial'
    let text = lang('tip-tutorial')
    text = text.replace('[', '<a target="_blank" href="https://github.com/plepe/OpenStreetBrowser/blob/master/doc/Tutorial.md">')
    text = text.replace(']', '</a>')
    tutorial.innerHTML = text
    controls.appendChild(tutorial)

    input.onclick = (e) => {
      this.submit()
      e.preventDefault()
    }

    this.window.show()
  }

  submit () {
    const err = customCategoryTest(this.textarea.value)
    if (err) {
      global.alert(err)
      return false
    }

    this.applyContent(this.textarea.value)
    return true
  }

  applyContent (content) {
    this.content = content
    this.repository.saveCategory(this.content, {}, () => {})

    if (this.textarea) {
      this.textarea.value = content
      this.updateDownload()
    }

    const id = md5(content)
    this.id = id

    if (this.category) {
      this.category.remove()
      this.category = null
    }

    OpenStreetBrowserLoader.getCategory('custom/' + id, {}, (err, category) => {
      if (err) {
        return global.alert(err)
      }

      this.category = category
      this.category.setParentDom(document.getElementById('contentListAddCategories'))

      this.category.open()
    })
  }

  updateDownload () {
    const file = new Blob([this.textarea.value], { type: 'application/yaml' })
    this.inputDownload.href = URL.createObjectURL(file)
    this.inputDownload.download = md5(this.textarea.value) + '.yaml'
  }
}

function editCustomCategory (id, category) {
  const done = editors.filter(editor => {
    if (editor.id === id) {
      editor.edit()
      return true
    }
  })

  if (!done.length) {
    const editor = new CustomCategoryEditor(repository)
    editor.load(id, (err) => {
      if (err) { return global.alert(err) }
      editor.category = category
      editor.edit()
    })
  }
}

hooks.register('browser-more-categories', (browser, parameters) => {
  const content = browser.dom

  if (!Object.keys(parameters).length) {
    const block = document.createElement('div')
    block.setAttribute('weight', 0)
    content.appendChild(block)

    const header = document.createElement('h4')
    header.innerHTML = lang('customCategory:header')
    block.appendChild(header)

    const ul = document.createElement('ul')

    let li = document.createElement('li')
    let a = document.createElement('a')
    a.innerHTML = lang('customCategory:create')
    a.href = '#'
    a.onclick = (e) => {
      const editor = new CustomCategoryEditor(repository)
      editor.edit()
      browser.close()
      e.preventDefault()
    }
    li.appendChild(a)
    ul.appendChild(li)

    li = document.createElement('li')
    a = document.createElement('a')
    a.innerHTML = lang('customCategory:list')
    a.href = '#more-categories?custom=list'
    li.appendChild(a)
    ul.appendChild(li)

    block.appendChild(ul)
    browser.catchLinks()
  } else if (parameters.custom === 'list') {
    customCategoriesList(browser, parameters)
  }
})

function customCategoriesList (browser, options) {
  browser.dom.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  repository.listCategories({},
    (err, result) => {
      browser.dom.innerHTML = ''

      const ul = document.createElement('ul')
      browser.dom.appendChild(ul)

      result.forEach(cat => {
        const li = document.createElement('li')

        const a = document.createElement('a')
        a.href = '#categories=custom/' + cat.id
        a.appendChild(document.createTextNode(cat.name))
        li.appendChild(a)

        const edit = document.createElement('a')
        edit.onclick = (e) => {
          editCustomCategory(cat.id)
          e.preventDefault()
        }
        edit.innerHTML = ' <i class="fa fa-pen"></i>'
        li.appendChild(edit)

        ul.appendChild(li)
      })

      browser.catchLinks()
    })
}

const repository = new CustomCategoryRepository()
hooks.register('init', () => {
  OpenStreetBrowserLoader.registerRepository('custom', repository)
})

hooks.register('category-overpass-init', (category) => {
  const m = category.id.match(/^custom\/(.*)$/)
  if (m) {
    const id = m[1]

    if (category.tabEdit) {
      category.tools.remove(this.category.tabEdit)
    }

    category.tabEdit = new tabs.Tab({
      id: 'edit',
      weight: 9
    })
    category.tools.add(category.tabEdit)
    category.tabEdit.header.innerHTML = '<i class="fa fa-pen"></i>'
    category.tabEdit.header.title = lang('edit')
    category.tabEdit.on('select', () => {
      category.tabEdit.unselect()
      editCustomCategory(id, category)
    })

    if (!category.tabShare) {
      const url = location.origin + location.pathname + '#categories=custom/' + id

      category.tabShare = new tabs.Tab({
        id: 'share',
        weight: 10
      })
      category.tools.add(category.tabShare)
      category.shareLink = document.createElement('a')
      category.shareLink.href = url
      category.shareLink.innerHTML = '<i class="fa fa-share-alt"></i>'

      category.tabShare.header.appendChild(category.shareLink)
      category.tabShare.header.className = 'share-button'
      category.tabShare.on('select', () => {
        category.tabShare.unselect()
        navigator.clipboard.writeText(url)

        const notify = document.createElement('div')
        notify.className = 'notify'
        notify.innerHTML = lang('copied-clipboard')
        category.tabShare.header.appendChild(notify)
        global.setTimeout(() => category.tabShare.header.removeChild(notify), 2000)
      })
    }
  } else {
    if (category.tabClone) {
      category.tools.remove(this.category.tabClone)
    }

    category.tabClone = new tabs.Tab({
      id: 'clone',
      weight: 9
    })
    category.tools.add(category.tabClone)
    category.tabClone.header.innerHTML = '<i class="fa fa-clone"></i>'
    category.tabClone.header.title = lang('customCategory:clone')
    category.tabClone.on('select', () => {
      category.tabClone.unselect()

      const clone = new CustomCategoryEditor(repository)
      clone.edit()

      category.repository.file_get_contents(category.data.fileName, {},
        (err, content) => {
          if (err) {
            console.error(err)
            return global.alert(err)
          }

          if (category.data.format === 'json') {
            content = JSON.parse(content)
            content = jsonMultilineStrings.join(content, { exclude: [['const'], ['filter']] })
            content = yaml.dump(content, {
              lineWidth: 9999
            })
          }

          clone.applyContent(content)
          category.close()
        }
      )
    })
  }
})

function customCategoryTest (value) {
  if (!value) {
    return new Error('Empty category')
  }

  let data
  try {
    data = yaml.load(value)
  } catch (e) {
    return e
  }

  if (!data || typeof data !== 'object') {
    return new Error('Data can not be parsed into an object')
  }

  if (!('query' in data)) {
    return new Error('No "query" defined!')
  }

  if (typeof data.query === 'string') {
    const r = customCategoryTestQuery(data.query)
    if (r) { return r }
  } else if (data.query === null) {
    return new Error('No "query" defined!')
  } else if (Object.values(data.query).length) {
    for (const z in data.query) {
      const r = customCategoryTestQuery(data.query[z])
      if (r) { return new Error('Query z' + z + ': ' + r) }
    }
  } else {
    return new Error('"query" can not be parsed!')
  }

  const fields = ['feature', 'memberFeature']
  for (let i1 = 0; i1 < fields.length; i1++) {
    const k1 = fields[i1]
    if (data[k1]) {
      for (const k2 in data[k1]) {
        const err = customCategoryTestCompile(data[k1][k2])
        if (err) {
          return new Error('Compiling /' + k1 + '/' + k2 + ': ' + err.message)
        }

        if (k2 === 'style' || k2.match(/^style:/)) {
          for (const k3 in data[k1][k2]) {
            const err = customCategoryTestCompile(data[k1][k2][k3])
            if (err) {
              return new Error('Compiling /' + k1 + '/' + k2 + '/' + k3 + ': ' + err.message)
            }
          }
        }
      }
    }
  }
}

function customCategoryTestCompile (data) {
  if (typeof data !== 'string' || data.search('{') === -1) {
    return
  }

  let template
  try {
    template = OverpassLayer.twig.twig({ data, rethrow: true })
  } catch (e) {
    return e
  }

  const fakeOb = {
    id: 'n1',
    sublayer_id: 'main',
    osm_id: 1,
    type: 'node',
    tags: {
      foo: 'bar'
    },
    map: {
      zoom: 15,
      metersPerPixel: 0.8
    }
  }

  try {
    template.render(fakeOb)
  } catch (e) {
    return e
  }
}

function customCategoryTestQuery (str) {
  if (typeof str !== 'string') {
    return 'Query is not a string!'
  }

  // make sure the request ends with ';'
  if (!str.match(/;\s*$/)) {
    str += ';'
  }

  try {
    new OverpassFrontendFilter(str)
  } catch (e) {
    return e
  }
}
