const weightSort = require('weight-sort')

class Filter {
  constructor (def, layer) {
    this.layer = layer

    for (var k in def) {
      var f = def[k]
      f.name = lang(f.name)

      if (!('key' in f)) {
        f.key = k
      }

      f.change_on = 'keyup'

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
          toCheck: d.toCheck || [ k ],
          value: data[k],
          op: '='
        }

        if ('op' in d) {
          v.op = d.op
        }

        if (v.op === 'strsearch') {
          v.value = v.value
            .split(/ /g).
            filter(s => s !== '').
            map(s => new RegExp(s, 'i'))
        }

        this.additionalFilter.push(v)
      }

      this.layer.check_update_map()
    }.bind(this)
    
    parentNode.appendChild(this.dom)
  }

  _checkValue (value, filter) {
    switch (filter.op) {
      case '=':
        if (value !== filter.value) {
          return false
        }
        break;
      case 'has':
        if (!(value)
            || (value.split(/;/g).indexOf(filter.value) === -1)) {
          return false
        }
        break;
      case 'strsearch':
        if (!value) {
          return false
        }

        for (var k in filter.value) {
          if (!value.match(filter.value[k])) {
            return false
          }
        }
        break;
    }

    return true
  }

  check (ob) {
    for (let i in this.additionalFilter) {
      let filter = this.additionalFilter[i]

      if (filter.toCheck
          .map(k => this._checkValue(ob.tags[k], filter))
          .indexOf(true) === -1) {
        return false
      }
    }

    return true
  }

  refresh () {
    this.formFilter.refresh()
  }
}

module.exports = Filter
