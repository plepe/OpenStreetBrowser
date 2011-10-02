function talk(param, div) {
  // remove
  this.remove=function() {
  }

  // load
  this.load=function() {
    var param={ page: this.page };
    if(this.version)
      param.version=this.version;

    ajax("talk_load", param, this.load_callback.bind(this));
  }

  // load_callback
  this.load_callback=function(ret) {
    ret=ret.return_value;
    this.content=ret.content;

    this.show_format();
  }

  // save
  this.save=function() {
    var param={
      page: this.page,
      msg: "Update talk page",
    };

    ajax("talk_save", param, this.content, this.save_callback.bind(this));
  }

  // save_callback
  this.save_callback=function(ret) {
    alert(lang("saved"));
  }

  // call_save
  this.call_save=function() {
    this.content=creole_replace_save(this.form.elements.content.value);
    this.save();

    this.show_format();
  }

  // show_history
  this.show_history=function() {
    dom_clean(this.tool_div);

    var input=dom_create_append(this.tool_div, "input");
    input.type="button";
    input.value=lang("show");
    input.onclick=this.show_format.bind(this);

    this.content_div.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

    ajax("talk_history", { page: this.page }, this.show_history_callback.bind(this));
  }
  
  // show_history_callback
  this.show_history_callback=function(ret) {
    ret=ret.return_value;

    dom_clean(this.content_div);
    dom_create_append(this.content_div, "ul");

    for(var i in ret) {
      var e=ret[i];

      var li=dom_create_append(this.content_div, "li");

      var a=dom_create_append(li, "a");
      dom_create_append_text(a, e.version_tags.date);
      a.href="#";
      a.onclick=talk_show.bind(this, { page: this.page, version: e.version });

      dom_create_append_text(li, " by ");

      // TODO: as soon as user_show() is implemented change to "a"
      var a=dom_create_append(li, "span");
      //a.href="javascript:user_show(\""+e.version_tags.user+"\")";
      dom_create_append_text(a, e.version_tags.user);
    }
  }

  // show_edit
  this.show_edit=function() {
    var active=true;
    if(current_user.username=="")
      active=false;

    dom_clean(this.tool_div);

    // no user ... disable
    if(!active) {
      var div_error=dom_create_append(this.tool_div, "div");
      div_error.className="error";
      dom_create_append_text(div_error, lang("attention")+": "+lang("error:not_logged_in"));
    }

    // tool_bar
    var input=dom_create_append(this.tool_div, "input");
    input.type="submit";
    input.value=lang("save");
    if(!active)
      input.disabled=true;

    var input=dom_create_append(this.tool_div, "input");
    input.type="button";
    input.value=lang("cancel");
    input.onclick=this.show_format.bind(this);

    // content
    dom_clean(this.content_div);
    var edit=dom_create_append(this.content_div, "textarea");
    edit.className="talk_edit_content";
    edit.name="content";
    edit.value=this.content;

    // advice
    var div=dom_create_append(this.content_div, "div");
    div.innerHTML=lang("creole:advice");
  }

  // show_format
  this.show_format=function() {
    dom_clean(this.tool_div);
    var input=dom_create_append(this.tool_div, "input");
    input.type="button";
    input.value=lang("edit");
    input.onclick=this.show_edit.bind(this);

    var input=dom_create_append(this.tool_div, "input");
    input.type="button";
    input.value=lang("history");
    input.onclick=this.show_history.bind(this);

    dom_clean(this.content_div);
    var format=creole(this.content)
    format.className="text";
    this.content_div.appendChild(format);
  }

  // constructor
  if(typeof param=="string") {
    this.page=param;
    param={};
  }
  else
    this.page=param.page;

  this.version=null;
  if(param.version)
    this.version=param.version;

  this.div=div;
  if(!this.div)
    this.div=document.createElement("div");

  this.form=dom_create_append(this.div, "form");
  this.form.action="#";
  this.form.onsubmit=this.call_save.bind(this);
  this.tool_div=dom_create_append(this.form, "div");
  this.content_div=dom_create_append(this.form, "div");
  this.content_div.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

  this.load();
}

function talk_show(param) {
  if(typeof(param)=="string") {
    param={ page: param };
  }

  var title="Talk "+param.page;
  if(param.version) {
    title+=" (version "+param.version+")";
  }

  var w=new win({ class: "talk", title: title });
  var t=new talk(param, w.content);
  w.onclose=t.remove.bind(t);
}

function talk_category_window_show(win, category) {
  var _tab=new tab({ class: "talk", title: "Talk" });
  var t=new talk("category:"+category.id);
  _tab.content.appendChild(t.div);
  win.tab_manager.register_tab(_tab);
  _tab.onclose=t.remove.bind(t);
}

register_hook("category_window_show", talk_category_window_show);
