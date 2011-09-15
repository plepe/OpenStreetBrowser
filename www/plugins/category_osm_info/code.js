function category_osm_info_show(cat_win, category) {
  var t=new tab({ title: lang("info") });
  cat_win.tab_manager.register_tab(t);

  t.content.innerHTML="foo bar";
}

register_hook("category_window_show", category_osm_info_show);
