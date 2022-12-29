const OverpassLayer = require('overpass-layer')
const tabs = require('modulekit-tabs')
const natsort = require('natsort').default

const state = require('./state')
const Filter = require('overpass-frontend').Filter
const getPathFromJSON = require('./getPathFromJSON')
const CategoryOverpass = require('./CategoryOverpass')

CategoryOverpass.defaultValues.filter = {
  title: {
    type: 'text',
    key: ['name', 'name:*', 'operator', 'operator:*', 'ref', 'ref:*'],
    name: '{{ trans("filter:title") }}',
    op: 'strsearch',
    weight: -1,
    show_default: true
  }
}

class CategoryOverpassFilter {
  constructor (master) {
    this.master = master
    this.data = this.master.data.filter

    this.tabFilter = new tabs.Tab({
      id: 'filter'
    })
    this.master.tools.add(this.tabFilter)

    this.tabFilter.header.innerHTML = '<i class="fa fa-filter" aria-hidden="true"></i>'
    this.tabFilter.header.title = lang('filter')

    this.domFilter = document.createElement('form')
    this.tabFilter.content.appendChild(this.domFilter)

    this.tabFilter.on('select', () => {
      this.formFilter.resize()
      this.formFilter.focus()
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
      masterOptions['button:add_element'] = '-- ' + lang('add_filter') + ' --'
      masterOptions.order = false
    }

    this.formFilter = new form('filter-' + this.master.id, this.data, masterOptions)
    this.formFilter.show(this.domFilter)
    this.formFilter.onchange = () => {
      const param = JSON.parse(JSON.stringify(this.formFilter.get_data()))

      this.applyParam(param)

      state.update()
    }

    this.master.on('setParam', this.setParam.bind(this))
    this.master.on('applyParam', (param) => {
      this.applyParam(param)

      if (!this.tabFilter.isSelected()) {
        this.tabFilter.select()
      }
    })
    this.master.on('open', this.openCategory.bind(this))
    this.master.on('stateGet', this.stateGet.bind(this))
    this.master.layer.on('twigData',
      (ob, data, result) => {
        result.filter = this.formFilter.get_data()
      }
    )
  }

  setParam (param) {
    this.formFilter.set_data(param)
  }

  applyParam (param) {
    this.additionalFilter = Object.keys(param).map(k => {
      let values = param[k]
      const d = this.data[k]

      if (values === null) {
        return d.emptyQuery
      }

      if (!Array.isArray(values)) {
        values = [values]
      }

      const ret = values.map(value => {
        if ('values' in d && value in d.values && typeof d.values[value] === 'object' && 'query' in d.values[value]) {
          const f = new Filter(d.values[value].query)
          return f.def
        } else if (d.queryTemplate) {
          const f = new Filter(decodeHTML(d.queryTemplate.render({ value: value }).toString()))
          return f.def
        }

        let v = {
          key: 'key' in d ? d.key : k,
          value: value,
          op: '='
        }

        if ('op' in d) {
          if (d.op === 'has_key_value') {
            v = {
              key: value,
              op: 'has_key'
            }
          } else {
            v.op = d.op
          }
        }

        if (Array.isArray(v.key)) {
          v = {
            or: v.key.map(
              key => {
                const v1 = { key, value: v.value, op: v.op }

                const m = key.match(/^(.*)\*(.*)/)
                if (m) {
                  v1.key = '^' + m[1] + '.*' + m[2]
                  v1.keyRegexp = true
                }

                return [v1]
              }
            )
          }
        }

        return [v]
      }).filter(f => f) // remove null values

      switch (ret.length) {
        case 0:
          return null
        case 1:
          return ret[0]
        default:
          return { or: ret }
      }
    }).filter(f => f) // remove null values

    if (this.additionalFilter.length === 0) {
      this.additionalFilter = []
    } else if (this.additionalFilter.length === 1) {
      this.additionalFilter = this.additionalFilter[0]
    } else {
      this.additionalFilter = { and: this.additionalFilter }
    }

    this.master.layer.setFilter(this.additionalFilter)
  }

  openCategory () {
    this.formFilter.resize()

    const param = JSON.parse(JSON.stringify(this.formFilter.get_data()))
    this.applyParam(param)
  }

  stateGet (param) {
    const data = this.formFilter.get_data()

    for (const k in data) {
      if (data[k]) {
        param[k] = data[k]
      }
    }
  }
}

register_hook('category-overpass-init', (category) => {
  if (category.data.filter) {
    new CategoryOverpassFilter(category)
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
