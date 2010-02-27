var categories={};

function category_rule_match(dom, cat, rule) {
  this.tags=new tags();
  this.tags.readDOM(dom);
  this.category=cat;
  this.rule=rule;
  this.id=dom.getAttribute("id");

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
    if(x=this.rule.tags.get("icon")) {
      var icon=x;
      var icon_data;

      li_style+="list-style-image: url('"+x+"'); ";
//      if(icon_data=icon.match(/^\[\[Image:(.*)\]\]$/)) {
//	icon_data=icon_data[1].replace(/ /g, "_");
//	li_style+="list-style-image: url('symbols/"+icon_data+"'); ";
//      }
    }
    var title="";

    ret="<li class='listitem' style=\""+li_style+"\" id='list_"+this.id+"' title='"+title+"'><element id='"+this.id+"' rule_id='"+this.rule.id+"'+><a href='#"+this.id+"' onMouseOver='set_highlight([\""+this.id+"\"])' onMouseOut='unset_highlight()'>"+name+"</a>"+add+"</element></li>\n";
    return ret;
  }
}

function category_rule(category, id, _tags) {
  // constructor
  if(!id) {
    this.id=uniqid();
    this.tags=new tags({ "name:en": "", match: "", kind: "", "description:en": "", icon: "", importance: "local" });
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

    ret+="<a id='edit_rule_"+id+"_name' href='javascript:edit_list_explode(\""+this.id+"\")'>";
    if(!this.tags.get("name"))
      ret+="New rule";
    else
      ret+=this.tags.get("name");
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
  // load_callback
  this.load_callback=function(data) {
    var xml=data.responseXML;
    var list=xml.firstChild;
    
    this.tags.readDOM(list);

    this.version=list.getAttribute("version");

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

    category_list[this.id]=[];
    lang_str["cat:"+this.id]=[ this.tags.get("name") ];
    show_list();
  }

  // load
  this.load=function() {
    ajax_direct("categories.php", { todo: "load", id: this.id }, this.load_callback.bind(this));
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
    this.tags=new tags({ "name:en": "", "descprition:en": "", "lang": "en" });
  }

  categories[this.id]=this;

  this.make_list=function(dom) {
    var ret="";
    var matches=dom.getElementsByTagName("match");

    if(matches.length==0) {
      ret+=t("nothing found")+"\n";
    }

    for(var mi=0; mi<matches.length; mi++) {
      var match=matches[mi];
      var rule=this.get_rule(match.getAttribute("rule_id"));
      if(rule) {
	var m=rule.load_entry(match);
	ret+=m.list_entry();
	//dyn_overlay_show(cat_id, place);
      }
    }

    //dyn_overlays_showall(cat_id);

    if(dom.getAttribute("complete")!="true") {
      ret+="<a id='more_"+this.id+"' href='javascript:list_more(\""+this.id+"\")'>"+t("more")+"</a>\n";
    }

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
    ret+="<div id='el_list_"+this.id+"'></div>\n";
    for(i=0; i<this.rules.length; i++) {
      ret+=this.rules[i].editor();
    }
    ret+="<a href='javascript: edit_list_new_rule(\""+this.id+"\")'>Add rule</a>\n";
    ret+="<br/>\n";

    ret+="<input type='submit' value='Save'/>\n";
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
    ellist.innerHTML+=el.editor(true);
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
    ret+=this.tags.xml("  ");

    ret.list=[];
    for(var i=0; i<this.rules.length; i++) {
      ret+="  <rule id=\""+this.rules[i].id+"\">\n";
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

function load_category(id) {
  if(edit_list_win) {
    edit_list_win.close();
    edit_list_win=null;
  }

  new category(id);
}

function lists_list_callback(data) {
  var list=data.responseXML;
  var obs=list.getElementsByTagName("list");
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
    if(inputs[i].name.match(/^list_/)) {
      var l=document.createElement("span");
      l.className="list_tools";
      l.innerHTML="<a href='javascript:edit_list(\""+inputs[i].name+"\")'>edit</a>\n";
      inputs[i].parentNode.parentNode.insertBefore(l, inputs[i].parentNode.parentNode.firstChild);
    }
  }

  div.innerHTML+="<a href='javascript:list_categories()'>"+t("more_categories")+"</a>\n";
}

register_hook("show_list", categories_show_list);


