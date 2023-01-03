const tabs = require('modulekit-tabs')
const natsort = require('natsort').default

const state = require('./state')
const getPathFromJSON = require('./getPathFromJSON')
const CategoryOverpass = require('./CategoryOverpass')

class CategoryOverpassConfig {
  constructor (master) {
    this.master = master
    this.data = this.master.data.config

    this.tabConfig = new tabs.Tab({
      id: 'config'
    })
    this.master.tools.add(this.tabConfig)

    this.tabConfig.header.innerHTML = '<i class="fa fa-cog" aria-hidden="true"></i>'
    this.tabConfig.header.title = lang('config')

    this.domConfig = document.createElement('form')
    this.tabConfig.content.appendChild(this.domConfig)

    this.tabConfig.on('select', () => {
      this.formConfig.resize()
      this.formConfig.focus()
    })

    for (const k in this.data) {
      const f = this.data[k]
      if ('name' in f && typeof f.name === 'string') {
        global.currentCategory = this.master
        const t = OverpassLayer.twig.twig({ data: f.name, autoescape: true })
        f.name = decodeHTML(t.render({}).toString())
      } else if (!('name' in f)) {
        f.name = lang('tag:' + k)
      }

      if ('query' in f) {
        f.queryTemplate = OverpassLayer.twig.twig({ data: f.query, autoescape: false })
      }

      if ('values' in f) {
        const valueNameTemplate = OverpassLayer.twig.twig({ data: f.valueName || '{{ value }}', autoescape: true })

        if (typeof f.values === 'string') {
          const valuesTemplate = OverpassLayer.twig.twig({ data: f.values, autoescape: true })
          const div = document.createElement('div')
          div.innerHTML = valuesTemplate.render(this.master.data)

          const options = div.getElementsByTagName('option')
          f.values = {}

          for (let i = 0; i < options.length; i++) {
            const option = options[i]

            const k = option.value
            f.values[k] = {}

            Array.from(option.attributes).forEach(attr => {
              f.values[k][attr.name] = attr.value
            })

            if (option.textContent) {
              f.values[k].name = option.textContent
            }
          }
        }

        if (Array.isArray(f.values) && f.valueName) {
          const newValues = {}
          f.values.forEach(value => {
            newValues[value] = decodeHTML(valueNameTemplate.render({ value }).toString())
          })
          f.values = newValues
        } else if (typeof f.values === 'object') {
          for (const k1 in f.values) {
            if (typeof f.values[k1] === 'string') {
              const t = OverpassLayer.twig.twig({ data: f.values[k1], autoescape: true })
              f.values[k1] = decodeHTML(t.render({}).toString())
            } else if (typeof f.values[k1] === 'object') {
              if (!('name' in f.values[k1])) {
                f.values[k1].name = decodeHTML(valueNameTemplate.render({ value: k1 }).toString())
              } else if (f.values[k1].name) {
                const t = OverpassLayer.twig.twig({ data: f.values[k1].name, autoescape: true })
                f.values[k1].name = decodeHTML(t.render({}))
              }
            }
          }
        }

        if (!('sort' in f) || (f.sort === 'natsort')) {
          const v = {}
          const sorter = natsort({ insensitive: true })
          const keys = Object.keys(f.values)

          keys
            .sort((a, b) => {
              const weight = (f.values[a].weight || 0) - (f.values[b].weight || 0)
              if (weight !== 0) {
                return weight
              }

              return sorter(f.values[a].name, f.values[b].name)
            })
            .forEach(k => { v[k] = f.values[k] })

          f.values = v
        }
      }

      if ('placeholder' in f && typeof f.placeholder === 'string') {
        const t = OverpassLayer.twig.twig({ data: f.placeholder, autoescape: true })
        f.placeholder = decodeHTML(t.render({}).toString())
      }
    }

    const masterOptions = {
      change_on_input: true
    }
    if (Object.keys(this.data).length > 1) {
      masterOptions.type = 'form_chooser'
      masterOptions['button:add_element'] = '-- ' + lang('add_config') + ' --'
      masterOptions.order = false
    }

    this.formConfig = new form('config-' + this.master.id, this.data, masterOptions)
    this.formConfig.show(this.domConfig)
    this.formConfig.onchange = () => {
      const param = JSON.parse(JSON.stringify(this.formConfig.get_data()))

      this.applyParam(param)

      state.update()
    }

    this.master.on('setParam', this.setParam.bind(this))
    this.master.on('applyParam', (param) => {
      const v = {}
      for (const k in param) {
        const m = k.match(/^config\.(.*)$/)
        if (m) {
          v[m[1]] = param[k]
        }
      }

      this.applyParam(v)
    })
    this.master.on('open', this.openCategory.bind(this))
    this.master.on('stateGet', this.stateGet.bind(this))
    this.master.layer.on('twigData',
      (ob, data, result) => {
        result.config = this.formConfig.get_data()
      }
    )
    this.master.on('updateInfo',
      (result) => {
        result.config = this.formConfig.get_data()
      }
    )
  }

  setParam (param) {
    this.formConfig.set_data(param)
  }

  applyParam (param) {
    this.master.layer.recalc()
    this.master.updateInfo()
  }

  openCategory () {
    this.formConfig.resize()

    const param = JSON.parse(JSON.stringify(this.formConfig.get_data()))
    this.applyParam(param)
  }

  stateGet (param) {
    const data = this.formConfig.get_data()

    for (const k in data) {
      if (data[k] != this.data[k].default) {
        param['config.' + k] = data[k]
      }
    }
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.config) {
    new CategoryOverpassConfig(category)
  }
})

function decodeHTML (str) {
  if (typeof str === 'undefined') {
    return '-- undefined --'
  }

  return str
    .replace(/&#039;/g, '\'')
    .replace(/&quot;/g, '"')
    .replace(/&gt;/g, '>')
    .replace(/&lt;/g, '<')
}
