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
  this.activate=function() {
    if(this.manager)
      this.manager.activate_toolbox(this);
  }

  // deactivate
  this.deactivate=function() {
    if(this.manager)
      this.manager.activate_toolbox(-1);
  }

  // notify_deactivate
  this.notify_deactivate=function() {
    if(this.options.callback_deactivate)
      this.options.callback_deactivate();
  }

  // notify_activate
  this.notify_activate=function() {
    if(this.options.callback_activate)
      this.options.callback_activate();
  }

  // show_icon
  this.show_icon=function() {
    while(this.button.firstChild)
      this.button.removeChild(this.button.firstChild);

    var a=document.createElement("a");
    this.button.appendChild(a);

    var img=document.createElement("img");
    img.src=this.options.icon;
    if(this.options.icon_title)
      img.title=this.options.icon_title;
    a.appendChild(img);
  }

  // constructor
  this.options=options;
  this.content=document.createElement("div");
  this.content.className="toolbox";
  this.button=document.createElement("td");
  this.button.className="toolboxbutton";
  this.show_icon();
  this.manager=null;

  if(!this.options.weight)
    this.options.weight=0;
}
