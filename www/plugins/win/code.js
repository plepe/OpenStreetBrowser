var windows=[];
var win_root;

function win(options) {
  // close
  this.close=function() {
    this.win.parentNode.removeChild(this.win);
    delete windows[this.id];
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

  // Add window to div win_root (create if it doesn't exist)
  if(!win_root) {
    win_root=dom_create_append(document.body, "div");
    win_root.className="win_root";
  }
  win_root.appendChild(this.win);

  // Create title-bar
  this.title_bar=dom_create_append(this.win, "div");
  this.title_bar.className="win_title_bar";
  dom_create_append_text(this.title_bar, "Foobar");
  this.win.appendChild(this.title_bar);
  var close_button=dom_create_append(this.title_bar, "img");
  close_button.src="plugins/win/close.png";
  close_button.alt="close";
  close_button.className="win_close_button";
  close_button.onclick=this.close.bind(this);

  // Create div for content
  this.content=document.createElement("div");
  add_css_class(this.content, options.class);
  this.win.appendChild(this.content);

  this.id=uniqid();
  windows[this.id]=this;
}

function win_close(id) {
  windows[id].close();
}
