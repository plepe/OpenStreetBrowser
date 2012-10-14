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

  this.win=new win({ class: "icon_chooser", title: lang("icon_chooser:title") });
  this.win.content.appendChild(ajax_indicator_dom());

  var obj_list=icon_git.obj_list();

  dom_clean(this.win.content);
  var ul=dom_create_append(this.win.content, "ul");
  ul.className="list";

  for(var i=0; i<obj_list.length; i++) {
    obj_list[i].icon_chooser_write(ul, this.choose_callback.bind(this));
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

icon_obj.prototype.icon_chooser_write=function(ul, callback) {
  var li=dom_create_append(ul, "li");
  var img=dom_create_append(li, "img");
  img.src=this.url("preview.png");
  dom_create_append_text(li, " ");
  var name=this.tags.get("name")
  if(name)
    var txt=dom_create_append_text(li, name);

  this.save_callback=callback;
  li.onclick=this.callback.bind(this);
}


