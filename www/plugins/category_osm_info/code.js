function category_osm_info_show(cat_win, category) {
  var t=new tab({ title: lang("info") });
  cat_win.tab_manager.register_tab(t);

  var div=dom_create_append(t.content, "div");

  var h1=dom_create_append(div, "h1");
  dom_create_append_text(h1, lang("category")+" \""+category.tags.get_lang("name", ui_lang)+"\"");

  var desc;
  if(desc=category.tags.get_lang("description", ui_lang)) {
    var div_desc=dom_create_append(div, "div");
    dom_create_append_text(div_desc, desc);
  }

  for(var i=0; i<category.rules.length; i++) {
    var div=dom_create_append(t.content, "div");
    dom_create_append_text(div, category.rules[i].name());
  }
}

register_hook("category_window_show", category_osm_info_show);
