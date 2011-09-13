var windows=[];
var win_root;

function win(cl) {
  this.win=document.createElement("div");
  this.win.className=cl;

  // Add window to div win_root (create if it doesn't exist)
  if(!win_root) {
    win_root=dom_create_append(document.body, "div");
    win_root.className="win_root";
  }
  win_root.appendChild(this.win);

  // Create div for content
  this.content=document.createElement("div");
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
