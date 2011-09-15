function category_osm_info_show(cat_win, category) {
  var t=new tab({ title: lang("info") });
  cat_win.tab_manager.register_tab(t);

  var div=dom_create_append(t.content, "div");
  div.innerHTML=category.tags.get_lang("name", ui_lang);

  for(var i=0; i<category.rules.length; i++) {
    var div=dom_create_append(t.content, "div");
    dom_create_append_text(div, category.rules[i].name());
  }
}

register_hook("category_window_show", category_osm_info_show);
