var list_cache=[];
var categories={};
var importance=[ "international", "national", "regional", "urban", "suburban", "local" ];
var category_tags_default=
  { "name": "", "descprition": "", "lang": "en", "sub_categories": "" };
var category_rule_tags_default=
  { "name": "", "match": "", "description": "", "icon": "", 
    "importance": "urban", "type": "polygon;point" };

function get_category(id) {
  return categories[id];
}

list_cache.clean_up=function() {
  while(this.length>10) {
    this.shift();
  }
}

list_cache.search_element=function(viewbox, category) {
  for(var i=0; i<this.length; i++) {
    if((this[i].viewbox==viewbox)&&
       (this[i].category==category))
      return this[i];
  }

  return null;
}

list_cache.search=function(viewbox, category) {
  var ret;

  if(ret=list_cache.search_element(viewbox, category))
    return ret.text;

  return null;
}

function category_rule_match(dom, cat, rule) {
  this.tags=new tags();
  this.tags.readDOM(dom);
  this.category=cat;
  this.rule=rule;
  this.id=dom.getAttribute("id");
  this.id_split=split_semicolon(dom.getAttribute("id"));

  // text
  this.list_entry=function() {
    var ret="";
    var x;
    var name="";
    var add="";

    x=this.tags.get("display_name");
    if(!x)
      x=t("unnamed");
    name=x;

    if(x=this.tags.get("display_type"))
      add=" ("+x+")";

    var li_style="";
    if(x=this.tags.get("icon")) {
      var icon=x;
      var icon_data;

      li_style+="list-style-image: url('"+x+"'); ";
//      if(icon_data=icon.match(/^\[\[Image:(.*)\]\]$/)) {
//	icon_data=icon_data[1].replace(/ /g, "_");
//	li_style+="list-style-image: url('symbols/"+icon_data+"'); ";
//      }
    }
    if(this.rule.tags.get_lang("name", ui_lang))
      var title=this.rule.tags.get_lang("name", ui_lang);
    else
      var title=this.rule.tags.get("match");

    ret="<li class='listitem' style=\""+li_style+"\" id='list_"+this.id+"' title='"+title+"'><element id='"+this.id+"' rule_id='"+this.rule.id+"'+><a href='#"+this.id+"' onMouseOver='set_highlight([\""+this.id_split.join("\", \"")+"\"])' onMouseOut='unset_highlight()'>"+name+"</a>"+add+"</element></li>\n";
    return ret;
  }
}

function category_rule(category, id, _tags) {
  // constructor
  if(!id) {
    this.id=uniqid();
    this.tags=new tags(category_rule_tags_default);
  }
  else {
    this.id=id;
    this.tags=_tags;
  }
  this.data=[];
  this.category=category;

  // editor
  this.editor=function(visible) {
    var ret="";

    ret+="<div class='edit_list_rule' id='edit_list_rule_"+this.id+"'>\n";

    ret+="<a id='edit_rule_"+this.id+"_name' href='javascript:edit_list_explode(\""+this.id+"\")'>";
    if(this.tags.get_lang("name", ui_lang))
      ret+=this.tags.get_lang("name", ui_lang);
    else if(this.tags.get("match"))
      ret+=this.tags.get("match");
    else
      ret+="New rule";
    ret+="</a>\n";

    ret+="<div id='edit_rule_"+this.id+"_content'";
    if(!visible)
      ret+=" style='display: none;'";
    ret+">\n";
    ret+="<p>Tags (<a target='_new' href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/Edit_List'>Help</a>):\n";
    ret+="<div>\n";
    ret+=this.tags.editor();
    ret+="</div>\n";
    ret+="<input type='button' value='Ok' onClick='edit_list_rule_set(\""+this.id+"\")'>\n";
    ret+="</div>\n";

    ret+="</div>\n";

    return ret;
  }

  // load_entry
  this.load_entry=function(dom) {
    var id=dom.getAttribute("id");
    if(!this.data[id]) {
      this.data[id]=new category_rule_match(dom, this.category, this);
    }
    var match=this.data[id];
    var time=new Date();
    this.data[id].access=time.getTime();

    return this.data[id];
  }

}

function category(id) {
  // show
  this.show=function(id) {
    if((this.id==id)&&(this.overlay))
      this.overlay.show();
  }

  // hide
  this.hide=function(id) {
    if((this.id==id)&&(this.overlay))
      this.overlay.hide();
  }

  // load_callback
  this.load_callback=function(data) {
    var xml=data.responseXML;
    var list=xml.firstChild;
    
    this.tags.readDOM(list);

    this.version=list.getAttribute("version");

    this.rules=[];
    var cur=list.firstChild;
    while(cur) {
      if(cur.nodeName=="rule") {
        var t=new tags();
	t.readDOM(cur);
	this.rules.push(new category_rule(this, cur.getAttribute("id"), t));
      }
      cur=cur.nextSibling;
    }

    this.loaded=true;

    // register category
    category_list[this.id]=[];
    lang_str["cat:"+this.id]=[ this.tags.get_lang("name") ];

    // register overlay
    if(!(this.overlay=get_overlay(this.id)))
      this.overlay=new overlay(this.id);
    this.overlay.register_category(this);
    this.overlay.set_version(this.version);

    // if open request a reload
    show_list();

    // register hooks
    register_hook("show_category", this.show.bind(this), this);
    register_hook("hide_category", this.hide.bind(this), this);
  }

  // load
  this.load=function(version) {
    var param={ todo: "load" };

    param.id=this.id;

    if(version)
      param.version=version;

    ajax_direct("categories.php", param, this.load_callback.bind(this));
  }

  // constructor
  this.rules=[];
  if(id) {
    this.id=id;
    this.loaded=false;
    this.tags=new tags();
    this.load();
  }
  else {
    this.id="list_"+uniqid();
    this.loaded=true;
    this.tags=new tags(category_tags_default);
  }

  categories[this.id]=this;

  this.count_list=function(viewbox) {
    var cur_cache;
    if(!(cur_cache=list_cache.search_element(viewbox, this.id)))
      return 0;
    return cur_cache.data.length;
  }

  this.is_complete=function(viewbox) {
    var cur_cache;
    if(!(cur_cache=list_cache.search_element(viewbox, this.id)))
      return 0;
    return cur_cache.complete;
  }

  this.push_list=function(dom, viewbox) {
    var ret="";
    var matches=dom.getElementsByTagName("match");
    var last_importance="";

    if(this.version!=dom.getAttribute("version")) {
      this.load(dom.getAttribute("version"));
    }

    var cur_cache;
    if(!(cur_cache=list_cache.search_element(viewbox, this.id))) {
      cur_cache={
	viewbox: viewbox,
	category: this.id,
	data: [],
	complete: false,
	complete_importance: {},
      };
      list_cache.push(cur_cache);
      list_cache.clean_up();
    }

    for(var mi=0; mi<matches.length; mi++) {
      var match=matches[mi];
      var rule=this.get_rule(match.getAttribute("rule_id"));
      if(rule) {
	var match_ob=rule.load_entry(match);

	cur_cache.data.push(match_ob);
	call_hooks("category_load_match", this, match_ob)

	var mimp=match_ob.tags.get("importance");
	if(mimp!=last_importance) {
	  for(var i=0; i<importance.length; i++) {
	    if(mimp==importance[i])
	      break;
	    cur_cache.complete_importance[importance[i]]=true;
	  }
	  last_importance=mimp;
	}
      }
    }

    if(dom.getAttribute("complete")=="true") {
      cur_cache.complete=true;
      for(var i=0; i<importance.length; i++) {
	cur_cache.complete_importance[importance[i]]=true;
      }
    }

    call_hooks("category_loaded_matches", this, viewbox)

    return cur_cache;
  }

  this.write_list=function(viewbox, offset, limit) {
    var ret="";
    if(!offset)
      offset=0;
    if(!limit)
      limit=10;

    var cur_cache;
    if(!(cur_cache=list_cache.search_element(viewbox, this.id))) {
      return null;
    }

    if(cur_cache.data.length==0) {
      ret+=t("nothing found")+"\n";
    }

    var max=offset+limit;
    if(max>cur_cache.data.length)
      max=cur_cache.data.length;

    for(var i=offset; i<max; i++) {
      var match_ob=cur_cache.data[i];
      call_hooks("category_show_match", this, match_ob);
      ret+=match_ob.list_entry();
    }

    call_hooks("category_show_finished", this);

    return ret;
  }

  // editor
  this.editor=function() {
    if(this.win) {
      alert("There's already a window!");
      return;
    }

    if(!this.loaded) {
      alert("Not loaded yet!");
      return;
    }

    this.win=new win("edit_list");

    var ret="";
    ret ="<form id='edit_"+this.id+"' action='javascript: edit_list_set_list_data(\""+this.id+"\")'>\n";
    ret+=this.tags.editor();

    ret+="<hr>\n";
    ret+="<div id='el_list_"+this.id+"'>\n";
    for(i=0; i<this.rules.length; i++) {
      ret+=this.rules[i].editor();
    }
    ret+="</div>\n";
    ret+="<a href='javascript: edit_list_new_rule(\""+this.id+"\")'>Add rule</a>\n";
    ret+="<br/>\n";

    ret+="<input type='submit' value='Save'/>\n";
    ret+="<input type='button' value='Cancel' onClick='edit_list_cancel(\""+this.id+"\")'/>\n";
    ret+="</form>\n";

    this.win.content.innerHTML=ret;
  }

  // get_rule
  this.get_rule=function(id) {
    for(var i=0; i<this.rules.length; i++) {
      if(this.rules[i].id==id)
	return this.rules[i];
    }

    return null;
  }

  // edit_list_new_rule
  this.edit_list_new_rule=function() {
    if(!this.loaded) {
      alert("Not loaded yet!");
      return;
    }

    var el=new category_rule(this);
    this.rules.push(el);

    var ellist=document.getElementById("el_list_"+this.id);
    var x=document.createElement("div");
    x.innerHTML=el.editor(true);
    ellist.appendChild(x);
  }

  // set_list_data
  this.set_list_data=function() {
    if(!this.loaded) {
      alert("Not loaded yet!");
      return;
    }

    var ret="";
   
    ret="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

    ret+="<category id=\""+this.id+"\" version=\""+this.version+"\">\n";
    this.tags.editor_update();
    ret+=this.tags.xml("  ");

    ret.list=[];
    for(var i=0; i<this.rules.length; i++) {
      ret+="  <rule id=\""+this.rules[i].id+"\">\n";
      this.rules[i].tags.editor_update();
      ret+=this.rules[i].tags.xml("    ");
      ret+="  </rule>\n";
    }

    ret+="</category>\n";

    ajax_post("categories.php", { todo: 'save', id: this.id }, ret, this.save_callback.bind(this));
  }

  // save_callback
  this.save_callback=function(data) {
    var result=data.responseXML;
    
    var stat=result.getElementsByTagName("status");
    var stat=stat[0];

    switch(stat.getAttribute("status")) {
      case "ok":
	alert("Saved.");
	this.win.close();
	this.win=null;
	break;
      case "merge failed":
        this.resolve_conflict(stat.getAttribute("branch"), stat.getAttribute("version"));
	break;
      default:
	alert("Saved with status "+stat.getAttribute("status"));
    }
  }

  // cancel
  this.cancel=function() {
    this.win.close();
    this.win=null;
  }

  // destroy
  this.destroy=function() {
    // register category
    delete(category_list[this.id]);
    delete(categories[this.id]);
    show_list();

    // register overlay
    if(this.overlay)
      this.overlay.unregister_category(this);

    // unregister hooks
    unregister_object_hooks(this);
  }

}

function edit_list_new_rule(id) {
  categories[id].edit_list_new_rule();
}

function edit_list(id) {
  if(!id) {
    var l=new category();
    l.editor();
  }
  else {
    var l=categories[id];
    l.editor();
  }
}

function edit_list_rule_set(id) {
  var tdiv=document.getElementById("edit_rule_"+id+"_content");
  tdiv.style.display="none";
}

function edit_list_explode(id) {
  var tdiv=document.getElementById("edit_rule_"+id+"_content");
  tdiv.style.display="block";
}

function edit_list_set_list_data(id) {
  categories[id].set_list_data();
}

function edit_list_cancel(id) {
  categories[id].cancel();
}

function load_category(id) {
  if(edit_list_win) {
    edit_list_win.close();
    edit_list_win=null;
  }

  new category(id);
}

function lists_list_callback(data) {
  var list=data.responseXML;
  var obs=list.getElementsByTagName("category");
  var ret="";

  ret+="<p>Choose a category:<ul>\n";
  for(var i=0; i<obs.length; i++) {
    var ob=obs[i];

    ret+="<li><a href='javascript:load_category(\""+ob.getAttribute("id")+"\")'>"+
      ob.textContent+"</a></li>\n";
  }
  ret+="</ul>\n";

  ret+="<p><a href='javascript:edit_list()'>New category</a><br>\n";
  ret+="<p><a href='javascript:window_close(\""+edit_list_win.id+"\")'>Cancel</a><br>\n";
  edit_list_win.content.innerHTML=ret;
}

function list_categories() {
  edit_list_win=new win("edit_list");
  edit_list_win.content.innerHTML="Loading ...";
  ajax_direct("categories.php", { todo: "list" }, lists_list_callback);
}

function categories_show_list(div) {
  var inputs=div.getElementsByTagName("input");

  for(var i=0; i<inputs.length; i++) {
    var l=document.createElement("span");
    l.className="list_tools";
    l.innerHTML="<a href='javascript:edit_list(\""+inputs[i].name+"\")'>edit</a>\n";
    l.innerHTML+="<a href='javascript:destroy_category(\""+inputs[i].name+"\")'>X</a>\n";
    inputs[i].parentNode.parentNode.insertBefore(l, inputs[i].parentNode.parentNode.firstChild);
  }

  div.innerHTML+="<a href='javascript:list_categories()'>"+t("more_categories")+"</a>\n";
}

function destroy_category(id) {
  get_category(id).destroy();
}

register_hook("show_list", categories_show_list);


