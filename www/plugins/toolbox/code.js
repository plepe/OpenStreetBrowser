var toolbox_css;

// valid variables:
//   .content:    the div for the content of the toolbox
//
// valid options:
//   icon: 'file.png'
//   icon_title: tooltip for icon
//   callback_activate: function to be called when activating
//   callback_deactivate: function to be called when deactivating
//   weight: order of icons (-10 left ... +10 right)
function toolbox(options) {
  // activate
  this.activate=function(force) {
    if(this.manager) {
      this.manager.activate_toolbox(this,force);
    }
  }

  // deactivate
  this.deactivate=function() {
    if(this.manager) {
      this.manager.activate_toolbox(-1);
    }
  }

  // notify_deactivate
  this.notify_deactivate=function() {
    if(this.options.callback_deactivate) {
      this.options.callback_deactivate();
    }
  }

  // notify_activate
  this.notify_activate=function() {
    if(this.options.callback_activate) {
      this.options.callback_activate();
    }
  }

  // active - returns true if toolbox is currently active
  this.active=function() {
    return this.manager.toolboxes[this.manager.current_active]==this;
  }

  // show_icon
  this.show_icon=function() {
    while(this.button.firstChild) {
      this.button.removeChild(this.button.firstChild);
    }
    var img=document.createElement("img");
    img.src=this.options.icon;
    if(this.options.icon_title) {
      img.title=this.options.icon_title;
      this.button.appendChild(img);
    }
  }

  // keyshort_callback
  this.keyshort_callback=function(id) {
    this.activate();
  }

  // constructor
  this.options=options;
  this.content=document.createElement("div");
  this.content.className="toolbox";
  this.button=document.createElement("td");
  this.button.className="toolboxbutton";
  this.show_icon();
  this.manager=null;

  if(!this.options.weight) {
    this.options.weight=0;
  }

  if(this.options.keyshort) {
    if(!this.options.keyshort.callback)
      this.options.keyshort.callback=this.keyshort_callback.bind(this);

    if(typeof register_keyshort!="undefined")
      register_keyshort(this.options.keyshort);
  }
}

function toolbox_init() {
  if(toolbox_css)
    return;

  toolbox_css=document.createElement("style");
  toolbox_css.type="text/css";
  dom_create_append_text(toolbox_css, "div.toolbox_active { }");
  document.body.appendChild(toolbox_css);
}

function toolbox_window_resize() {
  var max_size=
    document.getElementById("menu").offsetTop-
    document.getElementById("toolbox_container").offsetTop;

  dom_clean(toolbox_css);
  dom_create_append_text(toolbox_css, "div.toolbox_active { max-height: "+
    (max_size/3)+"px; }");
  toolbox_manager.resize_toolbox();
}

register_hook("init", toolbox_init);
register_hook("window_resize", toolbox_window_resize);
