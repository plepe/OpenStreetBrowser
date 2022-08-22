const EventEmitter = require('events')

module.exports = class Window extends EventEmitter {
  constructor (options) {
    super()

    this.visible = false
    this.dom = document.createElement('div')
    this.dom.className = 'Window'

    this.header = document.createElement('div')
    this.header.className = 'header'
    this.header.innerHTML = options.title
    this.dom.appendChild(this.header)

    this.closeBtn = document.createElement('div')
    this.closeBtn.className = 'closeBtn'
    this.closeBtn.title = lang('close')
    this.closeBtn.onclick = (e) => {
      this.close()
      e.stopImmediatePropagation()
    }
    this.header.appendChild(this.closeBtn)

    this.content = document.createElement('div')
    this.content.className = 'content'
    this.dom.appendChild(this.content)

    dragElement(this.dom)

    this.dom.onclick = () => {
      if (!this.visible) { return }

      const activeEl = document.activeElement

      if (document.body.lastElementChild !== this.dom) {
        document.body.appendChild(this.dom)
        activeEl.focus()
      }
    }
  }

  show () {
    this.visible = true
    document.body.appendChild(this.dom)
    this.emit('show')
  }

  close () {
    this.visible = false
    document.body.removeChild(this.dom)
    this.emit('close')
  }
}

// copied from https://www.w3schools.com/HOWTO/howto_js_draggable.asp
// Make the DIV element draggable:
function dragElement(elmnt) {
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  if (elmnt.firstChild) {
    // if present, the header is where you move the DIV from:
    elmnt.firstChild.onmousedown = dragMouseDown;
  } else {
    // otherwise, move the DIV from anywhere inside the DIV:
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    // stop moving when mouse button is released:
    document.onmouseup = null;
    document.onmousemove = null;
  }
}
