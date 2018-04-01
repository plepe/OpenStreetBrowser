class Filter {
  constructor (def, layer) {
    this.layer = layer

    for (var k in def) {
      var f = def[k]
      f.name = lang(f.name)

      if (!('key' in f)) {
        f.key = k
      }

      if (f.values === 'auto') {
        delete f.values
        f.values_func = { js: function (f) {
          var ret = {}
          var counts = {}

          for (var k in this.layer.bboxFeatures) {
            var ob = this.layer.bboxFeatures[k]
            var values = 'tags' in ob ? ob.tags[f.key] : null

            if (values) {
              if (f.op === 'has') {
                values = values.split(/;/g)
              } else {
                values = [ values ]
              }

              for (var i in values) {
                var v = values[i]

                if (v in ret) {
                  counts[v]++
                } else {
                  ret[v] = lang('tag:' + f.key + '=' + v)
                  counts[v] = 1
                }
              }
            }
          }

          for (var k in ret) {
            ret[k] = {
              count: counts[k],
              name: ret[k] + ' (' + counts[k] + ')'
            }
          }

          ret = weightSort(ret, {
            key: 'count',
            reverse: true
          })

          return ret
        }.bind(this, f) }
      }
    }

    this.def = def
  }

  form (parentNode) {
    this.dom = document.createElement('form')

    this.formFilter = new form(this.id, this.def,
      {
        'type': 'form_chooser',
        'button:add_element': '-- ' + lang('filter_results') + ' --',
        'order': false
      }
    )
    this.formFilter.show(this.dom)
    this.formFilter.onchange = function () {
      var data = this.formFilter.get_data()

      this.additionalFilter = []
      for (var k in data) {
        if (data[k] === null) {
          continue
        }

        var d = this.def[k]

        var v  = {
          key: k,
          value: data[k],
          op: '='
        }

        if ('op' in d) {
          v.op = d.op
        }

        this.additionalFilter.push(v)
      }

      this.layer.check_update_map()
    }.bind(this)
    
    parentNode.appendChild(this.dom)
  }

  check (ob) {
    for (var i in this.additionalFilter) {
      let filter = this.additionalFilter[i]

      if (filter.op === '=') {
        if (ob.tags[filter.key] !== filter.value) {
          return false
        }
      } else if (filter.op === 'has') {
        if (!(filter.key in ob.tags)
            || (ob.tags[filter.key].split(/;/g).indexOf(filter.value) === -1)) {
          return false
        }
      }
    }
    return true
  }

  refresh () {
    this.formFilter.refresh()
  }
}

module.exports = Filter
