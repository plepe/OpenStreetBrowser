var windows=[];
var win_root;

function win(options) {
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

  // Add window to div win_root (create if it doesn't exist)
  if(!win_root) {
    win_root=dom_create_append(document.body, "div");
    win_root.className="win_root";
  }
  win_root.appendChild(this.win);

  // Create div for content
  this.content=document.createElement("div");
  add_css_class(this.content, options.class);
  this.win.appendChild(this.content);

  this.id=uniqid();
  windows[this.id]=this;

  this.close=function() {
    this.win.parentNode.removeChild(this.win);
    delete windows[this.id];
  }
}

function win_close(id) {
  windows[id].close();
}
