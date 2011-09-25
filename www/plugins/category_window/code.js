function category_window(category) {
  // update
  this.window_show=function() {
    // Set window title
    this.win.set_title(lang("category")+
      " \""+category.tags.get_lang("name", ui_lang)+"\"");
    
    // Prepare window
    dom_clean(this.win.content);
    this.tab_manager=new tab_manager(this.win.content);

    // Call hooks to add content to window
    call_hooks("category_window_show", this, category);
  }

  // remove
  this.remove=function() {
    unregister_hooks_object(this);
  }

  // constructor
  this.win=new win({ class: "category_window", title: lang("category") });
  this.category=category;

  if(this.category.loaded) {
    this.window_show();
  }
  else {
    this.win.content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");
    register_hook("category_loaded", this.window_show.bind(this), this);
  }
}

function category_window_start_window(category) {
  new category_window(category);
}

function category_window_write_div(div, category) {
  // write_div
  var span=dom_create_append(div.header, "span");
  span.className="category_tools";
  div.header.insertBefore(span, div.header.firstChild);

  var img=dom_create_append(span, "img");
  img.className="category_tools";
  img.onclick=category_window_start_window.bind(null, category);
  img.src="plugins/category_window/info.png";
}

register_hook("category_write_div", category_window_write_div);
