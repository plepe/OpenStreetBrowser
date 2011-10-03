function category_history(id, cat_win) {
  // load
  this.load=function() {
    var param={
      id: this.id
    };

    ajax("category_history", param, this.load_callback.bind(this));
  }

  // load_callback
  this.load_callback=function(ret) {
    alert(ret);
  }

  // constructor
  this.id=id;
  if(!this.id)
    this.id="new";
  this.win=new tab({ class: "category_history", title: lang("category_history:name", 1) });
  this.win.content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");
  cat_win.tab_manager.register_tab(this.win);
  this.load();
}

// reacts on opening category window
function category_history_win_show(win, category) {
  new category_history(category.id, win);
}

register_hook("category_window_show", category_history_win_show);

