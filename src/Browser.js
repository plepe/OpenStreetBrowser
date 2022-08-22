const EventEmitter = require('events')
const queryString = require('query-string')

const domSort = require('./domSort')

module.exports = class Browser extends EventEmitter {
  constructor (id, dom) {
    super()

    this.id = id
    this.dom = dom
    this.history = []
  }

  buildPage (parameters) {
    this.clear()

    hooks.call('browser-' + this.id, this, parameters)
    this.emit('buildPage', parameters)
    this.parameters = parameters

    domSort(this.dom)
  }

  clear () {
    while (this.dom.lastChild) {
      this.dom.removeChild(this.dom.lastChild)
    }
  }

  catchLinks () {
    const links = this.dom.getElementsByTagName('a')
    Array.from(links).forEach(link => {
      const href = link.getAttribute('href')

      if (href && href.substr(0, this.id.length + 2) === '#' + this.id + '?') {
        link.onclick = () => {
          this.history.push(this.parameters)

          const parameters = queryString.parse(href.substr(this.id.length + 2))
          this.buildPage(parameters)

          return false
        }
      }
    })
  }

  close () {
    this.clear()
    this.emit('close')
  }
}
