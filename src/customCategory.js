const tabs = require('modulekit-tabs')
const yaml = require('js-yaml')
const md5 = require('md5')
const OverpassLayer = require('overpass-layer')

const Window = require('./Window')
const OpenStreetBrowserLoader = require('./OpenStreetBrowserLoader')

const cache = {}
const customCategories = []

class CustomCategoryRepository {
  constructor () {
  }

  load (callback) {
    callback(null)
  }

  clearCache () {
  }

  getCategory (id, options, callback) {
    if (id in cache) {
      return callback(null, yaml.load(cache[id]))
    }

    ajax('customCategory', { id }, (result) => {
      let data
      cache[id] = result

      try {
        data = yaml.load(result)
      }
      catch (e) {
        return global.alert(e)
      }

      if (Object.is(data) && !('name' in data)) {
        data.name = 'Custom ' + id.substr(0, 6)
      }

      callback(null, data)
    })
  }

  getTemplate (id, options, callback) {
    callback(null, '')
  }
}

class CustomCategory {
  constructor () {
    customCategories.push(this)
  }

  load (id, callback) {
    this.id = id
    ajax('customCategory', { id }, (result) => {
      this.content = result
      callback(null, result)
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
    this.window.content.appendChild(this.textarea)
    if (this.content !== undefined) {
      this.textarea.value = this.content
    }

    const controls = document.createElement('div')
    controls.className = 'controls'
    this.window.content.appendChild(controls)

    const input = document.createElement('input')
    input.type = 'submit'
    input.value = lang('apply')
    controls.appendChild(input)

    const tutorial = document.createElement('span')
    tutorial.className = 'tip-tutorial'
    let text = lang('tip-tutorial')
    text = text.replace('[', '<a target="_blank" href="https://github.com/plepe/OpenStreetBrowser/blob/master/doc/CategoryAsYAML.md">')
    text = text.replace(']', '</a>')
    tutorial.innerHTML = text
    controls.appendChild(tutorial)

    input.onclick = (e) => {
      const err = customCategoryTest(this.textarea.value)
      if (err) {
        return global.alert(err)
      }

      this.applyContent(this.textarea.value)
      ajax('customCategory', { content: this.textarea.value }, (result) => {})
      e.preventDefault()
    }

    this.window.show()
  }

  applyContent (content) {
    this.content = content

    const id = md5(content)
    this.id = id
    cache[id] = content

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
}


function editCustomCategory (id, category) {
  let done = customCategories.filter(customCategory => {
    if (customCategory.id === id) {
      customCategory.edit()
      return true
    }
  })

  if (!done.length) {
    const customCategory = new CustomCategory()
    customCategory.load(id, (err) => {
      if (err) { return global.alert(err) }
      customCategory.category = category
      customCategory.edit()
    })
  }
}

hooks.register('browser-more-categories', (browser, parameters) => {
  const content = browser.dom

  if (!Object.keys(parameters).length) {
    let block = document.createElement('div')
    block.setAttribute('weight', 0)
    content.appendChild(block)

    let header = document.createElement('h4')
    header.innerHTML = lang('customCategory:header')
    block.appendChild(header)

    let ul = document.createElement('ul')

    let li = document.createElement('li')
    let a = document.createElement('a')
    a.innerHTML = lang('customCategory:create')
    a.href = '#'
    a.onclick = (e) => {
      const category = new CustomCategory()
      category.edit()
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
  }
  else if (parameters.custom === 'list') {
    customCategoriesList(browser, parameters)
  }
})

function customCategoriesList (browser, options) {
  browser.dom.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> ' + lang('loading')

  ajax('customCategory', { 'list': true }, (result) => {
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

hooks.register('init', () => {
  OpenStreetBrowserLoader.registerRepository('custom', new CustomCategoryRepository())
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
    category.tabEdit.on('select', () => {
      category.tabEdit.unselect()
      editCustomCategory(id, category)
    })

    if (!category.tabShare) {
      category.tabShare = new tabs.Tab({
        id: 'share',
        weight: 10
      })
      category.tools.add(category.tabShare)
      category.tabShare.header.innerHTML = '<i class="fa fa-share-alt"></i>'
      category.tabShare.header.className = 'share-button'
      category.tabShare.on('select', () => {
        category.tabShare.unselect()
        const url = location.origin + location.pathname + '#categories=custom/' + id
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
    category.tabClone.header.innerHTML = '<i class="fa fa-pen"></i>'
    category.tabClone.on('select', () => {
      const clone = new CustomCategory()
      OpenStreetBrowserLoader.getFile(category.id, {},
        (err, result) => {
          if (err) { return global.alert(err) }
          clone.content = yaml.dump(result)
          clone.edit()
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
  }
  catch (e) {
    return e
  }

  const fields = ['feature', 'memberFeature']
  for (let i1 = 0; i1 < fields.length; i1++) {
    const k1 = fields[i1]
    if (data[k1]) {
      for (k2 in data[k1]) {
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

  try {
    OverpassLayer.twig.twig({ data })
  }
  catch (e) {
    return e
  }
}
