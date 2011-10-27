function category_osm_info_show(cat_win, category) {
  var t=new tab({ title: lang("category_osm_info:info") });
  cat_win.tab_manager.register_tab(t);
  t.content.innerHTML="<img src=\"img/ajax_loader.gif\" /> "+lang("loading");

  var content=document.createElement("div");
  content.className="category_osm_info";

  var div=dom_create_append(content, "div");

  var div1=category.category_osm_info_head();
  if(div1)
    div.appendChild(div1);

  var div1=category.category_osm_info_sub();
  if(div1) {
    var h2=dom_create_append(div, "h2");
    dom_create_append_text(h2, lang("category:sub_category", 2));

    div.appendChild(div1);
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
      var h2=dom_create_append(content, "h2");
      dom_create_append_text(h2, lang("importance:name")+": "+importance_lang(importance_levels[i]));
      var ul=dom_create_append(content, "ul");

      for(var j=0; j<imp_list[importance_levels[i]].length; j++) {
	var rule=imp_list[importance_levels[i]][j];

	var div=rule.category_osm_info();
	if(div)
	  ul.appendChild(div);
      }
    }
  }
  
  // show ...
  dom_clean(t.content);
  t.content.appendChild(content);
}

function category_osm_info_cat_construct(ob) {
  // category_osm_info_head
  ob.category_osm_info_head=function() {
    var div=document.createElement("div");
    div.className="category_header";

    var h1=dom_create_append(div, "h1");
    dom_create_append_text(h1, lang("category")+" \""+this.tags.get_lang("name", ui_lang)+"\"");

    var desc;
    if(desc=this.tags.get_lang("description", ui_lang)) {
      var div_desc=dom_create_append(div, "div");
      div_desc.className="description";
      dom_create_append_text(div_desc, desc);
    }

    return div;
  }

  // category_osm_info
  ob.category_osm_info=function() {
    var div=document.createElement("li");
    div.className="category_header";

    var h1=dom_create_append(div, "h3");
    dom_create_append_text(h1, lang("category")+" \""+this.tags.get_lang("name", ui_lang)+"\"");

    var desc;
    if(desc=this.tags.get_lang("description", ui_lang)) {
      var div_desc=dom_create_append(div, "div");
      div_desc.className="description";
      dom_create_append_text(div_desc, desc);
    }

    return div;
  }

  // category_osm_info_sub
  ob.category_osm_info_sub=function() {
    var list;
    if(!(list=this.tags.get("sub_categories")))
      return;

    list=split_semicolon(list);

    var ul=document.createElement("ul");
    for(var i=0; i<list.length; i++) {
      var ob=get_category("osm:"+list[i]);

      var li=ob.category_osm_info();
      if(li)
	ul.appendChild(li);
    }

    return ul;
  }
}


function category_osm_info_rule_construct(ob) {
  ob.category_osm_info=function() {
    var x;
    var div=document.createElement("li");

    if(x=get_icon(this.tags.get("icon"))) {
      if(x) {
	add_css_class(div, "list-image");
	div.style.backgroundImage="url('"+x.icon_url()+"')";
      }
    }

    var h3=dom_create_append(div, "h3");
    dom_create_append_text(h3, this.name());

    if(this.tags.get_lang("description", ui_lang)) {
      var div1=dom_create_append(div, "div");
      div1.className="description";

      dom_create_append_text(div1, t("category_rule_tag:description")+": "+this.tags.get_lang("description", ui_lang));
    }

    if(this.tags.get("match")) {
      var div1=dom_create_append(div, "div");
      div1.className="match";

      dom_create_append_text(div1, t("category_rule_tag:match")+": "+this.tags.get("match"));
    }

    return div;
  }
}

register_hook("category_window_show", category_osm_info_show);
register_hook("category_construct", category_osm_info_cat_construct);
register_hook("category_rule_construct", category_osm_info_rule_construct);
