function category_history(category, cat_win) {
  // show
  this.show=function() {
    var ret=this.data;

    dom_clean(this.content_div);
    dom_create_append(this.content_div, "ul");

    for(var i in ret) {
      var e=ret[i];

      var li=dom_create_append(this.content_div, "li");

      if(e.version==this.category.version)
	li.className="current";

      var a=dom_create_append(li, "a");
      var text=e.version_tags.comment || lang("no_message");
      dom_create_append_text(a, text);
      a.href=sprintf("javascript:category_show(\"osm:%s\", { version: \"%s\" })", this.category.id, e.version);

      dom_create_append_text(li, " (");

      var text=e.version_tags.date;
      if(!text)
	text="?";
      dom_create_append_text(li, text);

      dom_create_append_text(li, " by ");

      // TODO: as soon as user_show() is implemented change to "a"
      var a=dom_create_append(li, "span");
      //a.href="javascript:user_show(\""+e.version_tags.user+"\")";
      var text=e.version_tags.user;
      if(!text)
	text="?";
      dom_create_append_text(a, text);
      dom_create_append_text(a, ")");
    }
  }

  // load
  this.load=function() {
    var param={
      id: this.category.id
    };

    ajax("category_history", param, this.load_callback.bind(this));
  }

  // load_callback
  this.load_callback=function(ret) {
    this.data=ret.return_value;
    this.show();
  }

  // constructor
  this.category=category;
  this.win=new tab({ class: "category_history", title: lang("category_history:name", 1) });
  this.win.content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");
  this.content_div=this.win.content;
  cat_win.tab_manager.register_tab(this.win);
  this.load();
}

// reacts on opening category window
function category_history_win_show(win, category) {
  new category_history(category, win);
}

register_hook("category_window_show", category_history_win_show);

