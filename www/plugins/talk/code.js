function talk(page, div) {
  // remove
  this.remove=function() {
  }

  // load
  this.load=function() {
    ajax("talk_load", { page: this.page }, this.load_callback.bind(this));
  }

  // load_callback
  this.load_callback=function(ret) {
    ret=ret.return_value;
    this.content=ret;

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
    this.content=this.form.elements.content.value;
    this.save();

    this.show_format();
  }

  // show_edit
  this.show_edit=function() {
    dom_clean(this.tool_div);

    // no user ... disable
    if(current_user.username=="") {
      var div_error=dom_create_append(this.tool_div, "div");
      div_error.className="error";
      dom_create_append_text(div_error, lang("attention")+": "+lang("error:not_logged_in"));
    }

    // tool_bar
    var input=dom_create_append(this.tool_div, "input");
    input.type="submit";
    input.value=lang("save");
    if(current_user.username=="")
      input.disabled=true;

    // content
    dom_clean(this.content_div);
    var edit=dom_create_append(this.content_div, "textarea");
    edit.className="talk_edit_content";
    edit.name="content";
    edit.value=this.content;
  }

  // show_format
  this.show_format=function() {
    dom_clean(this.tool_div);
    var input=dom_create_append(this.tool_div, "input");
    input.type="button";
    input.value=lang("edit");
    input.onclick=this.show_edit.bind(this);

    dom_clean(this.content_div);
    this.content_div.appendChild(creole(this.content));
  }

  // constructor
  this.page=page;
  this.div=div;

  this.form=dom_create_append(this.div, "form");
  this.form.action="#";
  this.form.onsubmit=this.call_save.bind(this);
  this.tool_div=dom_create_append(this.form, "div");
  this.content_div=dom_create_append(this.form, "div");
  this.content_div.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

  this.load();
}

function talk_open_win(page) {
  var w=new win({ class: "talk", title: "Talk "+page });
  var t=new talk(page, w.content);
  w.onclose=t.remove.bind(t);
}
