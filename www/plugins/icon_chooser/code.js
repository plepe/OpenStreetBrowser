function icon_chooser(current, callback) {
  this.new_icon_callback=function(id) {
    callback(id);
    this.win.close();
    delete(this.win);
  }

  this.new_icon=function() {
    this.icon_editor=new icon_editor(null, this.new_icon_callback.bind(this));
  }

  this.choose_callback=function(id) {
    callback(id);
    this.win.close();
    delete(this.win);
  }

  this.cancel=function() {
    this.win.close();
    delete(this.win);
  }

  this.win=new win({ class: "edit_icon", title: lang("icon:editor") });
  this.win.content.appendChild(ajax_indicator_dom());

  var obj_list=icon_git.obj_list();

  dom_clean(this.win.content);
  var ul=dom_create_append(this.win.content, "ul");
  
  for(var i=0; i<obj_list.length; i++) {
    obj_list[i].write_chooser(ul, this.choose_callback.bind(this));
  }

  if(current_user.username) {
    var a=dom_create_append(this.win.content, "input");
    a.type="button";
    a.value=lang("icon_chooser:create");
    a.onclick=this.new_icon.bind(this);
  }

  var a=dom_create_append(this.win.content, "input");
  a.type="button";
  a.value=lang("cancel");
  a.onclick=this.cancel.bind(this);
}
