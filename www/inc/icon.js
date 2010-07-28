var icon_git;

function icon_obj(dir, id, files) {
  this.inheritFrom=git_obj;
  this.inheritFrom(dir, id, files);

  this.callback=function() {
    this.save_callback(this.id);
  }

  this.write_chooser=function(ul, callback) {
    var li=dom_create_append(ul, "li");
    var img=dom_create_append(li, "img");
    img.src=this.url("preview.png");
    //var txt=dom_create_append_text(li, this.files[0]);
    this.save_callback=callback;
    img.onclick=this.callback.bind(this);
  }

  this.icon_url=function() {
    return this.url("preview.png");
  }
}

function icon_editor(icon, callback) {
  this.new_icon_cancel=function() {
    data_dir.commit_cancel();
    this.win.close();
    delete(this.win);
  }

  this.new_icon_finish=function() {
    this.obj.save("einedatei", "huhu");
    var ret=data_dir.commit_end("foobar");
    callback(this.obj.id);
    this.win.close();
    delete(this.win);
  }

  this.win=new win("icon_editor");

  data_dir.commit_start();
  this.obj=icon_git.create_obj();
  var x=this.obj.upload_form("file.src");
  this.win.content.appendChild(x);

  var input=dom_create_append(this.win.content, "input");
  input.type="button";
  input.value="Save";
  input.onclick=this.new_icon_finish.bind(this);

  var input=dom_create_append(this.win.content, "input");
  input.type="button";
  input.value="Cancel";
  input.onclick=this.new_icon_cancel.bind(this);
}

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

  this.win=new win("edit_icon");
  this.win.content.innerHTML="Loading ...";

  var obj_list=icon_git.obj_list();

  this.win.content.innerHTML="";
  var ul=dom_create_append(this.win.content, "ul");
  
  for(var i=0; i<obj_list.length; i++) {
    obj_list[i].write_chooser(ul, this.choose_callback.bind(this));
  }

  var a=dom_create_append(this.win.content, "input");
  a.type="button";
  a.value="Create new icon";
  a.onclick=this.new_icon.bind(this);

  var a=dom_create_append(this.win.content, "input");
  a.type="button";
  a.value=t("cancel");
  a.onclick=this.cancel.bind(this);
}

function icon_init() {
  icon_git=new git_dir(data_dir, "icons", icon_obj);
}

register_hook("init", icon_init);
