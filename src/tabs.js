/**
 * @param {Object} options
 * @property {DOMNode} node the DOMNode which can be added to a parent
 * @property {Tab|null} selected The selected tab
 * @property {Tab[]} list List of available tabs
 */
function Tabs (options) {
  this.options = options || {}
  this.node = document.createElement('div')
  this.node.className = 'tabs'

  this.headers = document.createElement('ul')
  this.headers.className = 'tabs-list'
  this.node.appendChild(this.headers)

  this.selected = null
  this.list = []
}

/**
 * add a tab
 * @param {Tab} tab
 */
Tabs.prototype.add = function (tab) {
  this.headers.appendChild(tab.header)
  this.node.appendChild(tab.content)
  tab.master = this
}

/**
 * return a tab
 * @param {Tab|number|id} tab
 */
Tabs.prototype.get = function (tab) {
  if (typeof tab === 'object') {
    return tab
  }

  if (typeof tab === 'number') {
    return this.list[tab]
  }

  for (var i = 0; i < this.list.length; i++) {
    if (this.list[i].options.id === tab) {
      return this.list[i]
    }
  }
}

/**
 * select the specified tab
 * @param {Tab|number|id} tab
 */
Tabs.prototype.select = function (tab) {
  if (this.selected) {
    this.unselect()
  }

  tab = this.get(tab)
  this.selected = tab

  this.node.classList.add('has-selected')

  tab._select()
}

/**
 * unselect the currently selected tab
 */
Tabs.prototype.unselect = function () {
  if (this.selected) {
    this.selected._unselect()
    this.selected = null
  }

  this.node.classList.remove('has-selected')
}

/**
 * add a new tab pane to the tabs
 * @param {Object} options
 * @param {String} options.id ID of the tab
 * @property {DOMNode} content
 * @property {DOMNode} header
 * @property {Tabs} master
 */
function Tab (options) {
  this.options = options || {}
  this.master = null

  this.header = document.createElement('li')
  this.header.onclick = function () {
    this.toggle()
  }.bind(this)

  this.content = document.createElement('div')
  this.content.className = 'tabs-section'
}

/**
 * select this tab
 */
Tab.prototype.select = function () {
  this.master.select(this)
}

/**
 * toggle this tab (if selected, unselect)
 */
Tab.prototype.toggle = function () {
  if (this.master.selected === this) {
    this.master.unselect()
  } else {
    this.master.select(this)
  }
}

Tab.prototype._select = function () {
  this.header.classList.add('selected')
  this.content.classList.add('selected')
}

/**
 * unselect this tab
 */
Tab.prototype.unselect = function () {
  if (this.master.selected === this) {
    this.master.unselect()
  }
}

Tab.prototype._unselect = function () {
  this.header.classList.remove('selected')
  this.content.classList.remove('selected')
}

module.exports = {
  Tabs: Tabs,
  Tab: Tab
}
