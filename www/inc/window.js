function win(cl) {
  this.win=document.createElement("div");
  this.win.className=cl;
  this.content=document.createElement("div");

  document.body.appendChild(this.win);
  this.win.appendChild(this.content);

  this.close=function() {
    win.parentNode.removeChild(win);
    delete(this);
  }
}
