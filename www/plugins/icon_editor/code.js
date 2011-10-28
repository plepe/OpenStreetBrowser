var icon_tags_default={ "name": "", "source": "" };

function icon_editor(icon, callback) {
  this.new_icon_cancel=function() {
    data_dir.commit_cancel();
    this.win.close();
    delete(this.win);
  }

  this.check=function() {
    if(!this.summary.value) {
      alert("Please enter a summary of your changes!");
      return false;
    }

    return true;
  }

  this.new_icon_finish=function() {
    if(!this.check()) {
      return;
    }

    // generate and save tags.xml file
    var ret="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    ret+="<tags>\n";
    this.tags.editor_update();
    ret+=this.tags.xml("  ");
    ret+="</tags>\n";
    this.obj.save("tags.xml", ret);

    // end commit with some message
    var ret=data_dir.commit_end(this.summary.value);

    // clean up
    callback(this.obj.id);
    this.win.close();
    delete(this.win);
  }

  if(!data_dir.commit_start())
    return;

  if(icon)
    this.obj=icon;
  else
    this.obj=icon_git.create_obj();

  if(!this.obj)
    return;

  this.win=new win({ class: "icon_editor", title: lang("icon:editor") });

  var comment=dom_create_append(this.win.content, "div");
  dom_create_append_text(comment, lang("icon_editor:upload"));

  var x=this.obj.upload_form("file.src");
  this.win.content.appendChild(x);

  var comment=dom_create_append(this.win.content, "div");
  dom_create_append_text(comment, lang("tags", 2));
  
  var a=dom_create_append(comment, "a");
  a.target="_new";
  a.href="http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/Icons";
  dom_create_append_text(a, "("+lang("help")+")");

  dom_create_append_text(comment, ":");

  this.div_tags=dom_create_append(this.win.content, "div");
  if(icon)
    this.tags=new tags(icon.tags.data());
  else
    this.tags=new tags(icon_tags_default);
  this.tags.editor(this.div_tags);

  dom_create_append_text(this.win.content, lang("editor:request_message", 0, lang("save"))+":");
  this.summary=dom_create_append(this.win.content, "input");
  this.summary.name="summary";

  var input=dom_create_append(this.win.content, "input");
  input.type="button";
  input.value=lang("save");
  input.onclick=this.new_icon_finish.bind(this);

  var input=dom_create_append(this.win.content, "input");
  input.type="button";
  input.value=lang("cancel");
  input.onclick=this.new_icon_cancel.bind(this);
}
