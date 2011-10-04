function category_window(category) {
  // update
  this.window_show=function(loaded_category) {
    // wrong category which has been loaded
    if(this.category!=loaded_category)
      return;

    var title=lang("category")+
      " \""+category.tags.get_lang("name", ui_lang)+"\"";
    if(this.category.param&&this.category.param.version)
      title+=" (Version: "+this.category.param.version+")";

    // Set window title
    this.win.set_title(title);
    
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
    this.window_show(this.category);
  }
  else {
    this.win.content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");
    register_hook("category_loaded", this.window_show.bind(this), this);
  }
}

function category_window_start_window(category) {
  new category_window(category);
}

function category_show(id, param) {
  new category_window(get_category(id, param));
}

function category_window_write_div(div, category) {
  if(category.id=="root")
    return;

  // write_div
  var span=dom_create_append(div.header, "span");
  span.className="category_tools";
  div.header.insertBefore(span, div.header.firstChild);

  var img=dom_create_append(span, "img");
  img.className="category_tools";
  img.onclick=category_window_start_window.bind(null, category);
  img.src="img/info.png";
}

register_hook("category_write_div", category_window_write_div);
