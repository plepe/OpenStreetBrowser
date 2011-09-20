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

  // sort by importance
  var imp_list={};
  for(var i=0; i<category.rules.length; i++) {
    var imp=category.rules[i].tags.get("importance");

    if(imp) {
      if(!imp_list[imp])
	imp_list[imp]=[];

      imp_list[imp].push(category.rules[i]);
    }
  }

  // for each importance
  for(var i=0; i<importance_levels.length; i++) {
    if(imp_list[importance_levels[i]]) {
      var h2=dom_create_append(t.content, "h2");
      dom_create_append_text(h2, lang("importance:name")+": "+importance_lang(importance_levels[i]));

      for(var j=0; j<imp_list[importance_levels[i]].length; j++) {
	var rule=imp_list[importance_levels[i]][j];

	var div=dom_create_append(t.content, "div");
	dom_create_append_text(div, rule.name());
      }
    }
  }
}

register_hook("category_window_show", category_osm_info_show);
