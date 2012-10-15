var windows=[];
var win_root;
var win_mousemove_old;
var win_mousepos;
var win_currentdrag=null;
var win_oldmouseup;
var win_maxzindex=10000;

function win_mousemove(event) {
  var win_mouseold=win_mousepos;
  win_mousepos={ x: event.clientX, y: event.clientY };

  if(win_currentdrag) {
    win_currentdrag.move({
      x: win_mousepos.x-win_mouseold.x,
      y: win_mousepos.y-win_mouseold.y
    });
  }

  if(win_mousemove_old)
    return win_mousemove_old(event);
  else
    return 0;
}

// valid options:
//   class: class to add to the content-div
//   title: title to show in the title bar
//
// event handler:
//   onclose: Called when closing window

function win(options) {
  // close
  this.close=function() {
    if(this.onclose)
      this.onclose();

    this.win.parentNode.removeChild(this.win);
    unregister_hooks_object(this);
    delete windows[this.id];
  }

  // set_title
  this.set_title=function(title) {
    var current=this.title.firstChild;
    current.textContent=title;
  }

  // mousedown
  this.mousedown=function(event) {
    add_css_class(this.title_bar, "win_title_bar_moving");
    win_currentdrag=this;

    // Raise window to top
    if(this.win.style.zIndex!=win_maxzindex)
      this.win.style.zIndex=++win_maxzindex;
  }

  // mouseup
  this.mouseup=function(event) {
    del_css_class(this.title_bar, "win_title_bar_moving");
    win_currentdrag=null;
  }

  // move
  this.move=function(m) {
    this.win.style.top=(this.win.offsetTop+m.y)+"px";
    this.win.style.left=(this.win.offsetLeft+m.x)+"px";
  }

  // check options
  if(!options) {
    options={};
  }
  else if(typeof options=="string") {
    options={ "class": options };
  }

  // create window and set class(es)
  this.win=document.createElement("div");
  this.win.className="win"
  this.win.addEventListener("DOMSubtreeModified", this.resize.bind(this));
  register_hook("window_resize", this.resize.bind(this), this);

  // Add window to div win_root (create if it doesn't exist)
  if(!win_root) {
    win_root=dom_create_append(document.body, "div");
    win_root.className="win_root";

    win_mousemove_old=document.body.onmousemove;
    document.body.onmousemove=win_mousemove;
  }
  win_root.appendChild(this.win);

  // Create title-bar
  this.title_bar=dom_create_append(this.win, "table");
  this.title_bar.className="win_title_bar";
  var tr=dom_create_append(this.title_bar, "tr");

  this.title=dom_create_append(tr, "td");
  this.title.className="title";
  dom_create_append_text(this.title, options.title?options.title:"Window");
  this.title.onmousedown=this.mousedown.bind(this);
  this.title.onselectstart=function() {};

  // Close Button
  var td=dom_create_append(tr, "td");
  var close_button=dom_create_append(td, "img");
  close_button.src=modulekit_file("win", "close.png");
  close_button.alt="close";
  close_button.className="win_close_button";
  close_button.onclick=this.close.bind(this);

  // Create div for content
  this.content=document.createElement("div");
  this.content.className="content";
  add_css_class(this.content, options.class);
  this.win.appendChild(this.content);
  // Raise new window to top
  this.win.style.zIndex=++win_maxzindex;

  this.content_offset={
    'width': this.win.offsetWidth-this.content.clientWidth,
    'height': this.win.offsetHeight-this.content.clientHeight
  };

  this.id=uniqid();
  windows[this.id]=this;
}

  // resize
win.prototype.resize=function() {
    var border_margin=5;

    // move window to left/up when too wide/high
    if(this.win.offsetLeft+this.win.offsetWidth>window.innerWidth-border_margin*2)
      this.win.style.left=(window.innerWidth-this.win.offsetWidth-border_margin*2)+"px";
    if(this.win.offsetTop+this.win.offsetHeight>window.innerHeight-border_margin*2)
      this.win.style.top=(window.innerHeight-this.win.offsetHeight-border_margin*2)+"px";
    if(this.win.offsetLeft<border_margin)
      this.win.style.left=(border_margin)+"px";
    if(this.win.offsetTop<border_margin)
      this.win.style.top=(border_margin)+"px";

    if(!this.content)
      return;
    if(!this.content_offset)
      return;

    // if window is too wide/high, resize content
    this.content.style.maxWidth=
      (window.innerWidth-this.content_offset.width-border_margin*2)+"px";
    this.content.style.maxHeight=
      (window.innerHeight-this.content_offset.height-border_margin*2)+"px";
  }

function win_close(id) {
  windows[id].close();
}

function win_mouseup(event) {
  if(win_currentdrag)
    return win_currentdrag.mouseup(event);

  if(win_oldmouseup)
    return win_oldmouseup(event);
}

win_oldmouseup=window.onmouseup;
window.onmouseup=win_mouseup;
