var categories={};

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
	this.list.push(new category_rule(this, cur.getAttribute("id"), t));
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
  this.list=[];
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
    for(i=0; i<this.list.length; i++) {
      ret+=this.list[i].editor();
    }
    ret+="<a href='javascript: edit_list_new_rule(\""+this.id+"\")'>Add rule</a>\n";
    ret+="<br/>\n";

    ret+="<input type='submit' value='Save'/>\n";
    ret+="</form>\n";

    this.win.content.innerHTML=ret;
  }

  // edit_list_new_rule
  this.edit_list_new_rule=function() {
    if(!this.loaded) {
      alert("Not loaded yet!");
      return;
    }

    var el=new category_rule(this);
    this.list.push(el);

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
    for(var i=0; i<this.list.length; i++) {
      ret+="  <rule id=\""+this.list[i].id+"\">\n";
      ret+=this.list[i].tags.xml("    ");
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


