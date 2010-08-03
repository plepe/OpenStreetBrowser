var importance=[ "global", "international", "national", "regional", "urban", "suburban", "local" ];
var category_tags_default=
  { "name": "", "descprition": "", "lang": "en", "sub_categories": "" };
var category_rule_tags_default=
  { "name": "", "match": "", "description": "", "icon": "", 
    "importance": "urban", "type": "polygon;point" };

function category_osm(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  // shall_reload
  this.shall_reload=function(list, viewbox) {
    if(this.result&&this.result.viewbox==viewbox)
      return;
    
    if(!this.visible())
      return;

    if(list[this.id])
      return;
    list[this.id]=true;

    this.result=new this.result_ob(this);
    this.result.status="loading";
    this.write_div();

    for(var i=0; i<this.sub_categories.length; i++) {
      this.sub_categories[i].shall_reload(list, viewbox);
    }
  }

  // write_div
  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;

    if(!this.result||!this.result.data)
      return;

    var offset;
    var limit;

    if(!offset)
      offset=0;
    if(!limit)
      limit=10;

    var max=this.result.data.length;
//    if(limit<max)
//      max=limit;

    dom_clean(div.data);
    var ul=dom_create_append(div.data, "ul");

    for(var i=offset; i<max; i++) {
      var match_ob=this.result.data[i];
      call_hooks("category_show_match", this, match_ob);
      match_ob.write_list(ul);
    }

    dom_clean(div.more);
    if(!this.result.complete) {
      var a=dom_create_append(div.more, "a");
      a.onclick=this.show_more.bind(this);
      dom_create_append_text(a, t("more"));
    }
  }

  // show_more - load more data from server
  this.show_more=function() {
    this.request_data(true);
  }

  // request_data - load new data from server
  this.request_data=function(more) {
    var param={};
    param.viewbox=get_viewbox();
    param.zoom=get_zoom();
    param.category=this.id;
    param.count=10;

    if((this.result)&&(this.result.viewbox==param.viewbox)&&(!more)) {
      this.write_div();
      return;
    }
    if(!more) {
      this.result=new this.result_ob(this);
    }

    this.result.status="loading";
    this.write_div();

    var there=this.result.get_ids();
    if(there.length) {
      param.exclude=there.join(",");
    }

//    if(list_reload_working) {
//      list_reload_necessary=1;
//      return;
//    }

    //list_reload_working=1;
    ajax_direct("list.php", param, this.request_data_callback.bind(this));
  }

  // request_data_callback - called after loading new data from server
  this.request_data_callback=function(response) {
    var data=response.responseXML;
    //list_reload_working=0;

    if(!data) {
      alert("no data\n"+response.responseText);
      return;
    }

    var request;
    if(request=data.getElementsByTagName("request"))
      request=request[0];
    var viewbox=request.getAttribute("viewbox");

    var cats=data.getElementsByTagName("category");
    for(var cati=0; cati<cats.length; cati++) {
      this.recv(cats[cati], viewbox);
    }
  }

  this.result_ob=function(category) {
    // recv
    this.recv=function(dom, viewbox) {
      this.version=dom.getAttribute("version");
      this.viewbox=viewbox;
      this.complete=dom.getAttribute("complete")=="true";

      var matches=dom.getElementsByTagName("match");
      var last_importance="";

      for(var mi=0; mi<matches.length; mi++) {
	var match=matches[mi];
	var rule=category.get_rule(match.getAttribute("rule_id"));
	if(rule) {
	  var match_ob=rule.load_entry(match);

	  this.data.push(match_ob);
	  call_hooks("category_load_match", this, match_ob)

	  var mimp=match_ob.tags.get("importance");
	  if(mimp!=last_importance) {
	    for(var i=0; i<importance.length; i++) {
	      if(mimp==importance[i])
		break;
	      this.complete_importance[importance[i]]=true;
	    }
	    last_importance=mimp;
	  }
	}
      }

      this.status="recv";
    }

    // get_ids - get the ids of all objects
    this.get_ids=function() {
      var ret=[];

      for(var i=0; i<this.data.length; i++) {
	ret.push(this.data[i].id);
      }

      return ret;
    }

    // constructor
    this.version=0;
    this.status="new";
    this.category=category;
    this.complete=false;
    this.complete_importance=false;
    this.data=[];
    this.viewbox=null;
  }

  // recv
  this.recv=function(dom, viewbox) {
    if(dom.getAttribute("id")!=this.id) {
      alert("category "+this.id+": recv called with wrong id: "+dom.getAttribute("id"));
      return;
    }

    this.result.recv(dom, viewbox);

    call_hooks("category_loaded_matches", this, viewbox)

    this.write_div();
  }

  // load_def
  this.load_def=function(version) {
    var param={ todo: "load" };
    param.id=this.id;

    if(version)
      param.version=version;

    ajax_direct("categories.php", param, this.load_def_callback.bind(this));
  }

  // load_def_callback
  this.load_def_callback=function(response) {
    var data=response.responseXML;
    var cat_data=data.firstChild;

    if(cat_data)
      this.tags.readDOM(cat_data);

    this.version=cat_data.getAttribute("version");
    this.rules=[];

    var cur=cat_data.firstChild;
    while(cur) {
      if(cur.nodeName=="rule") {
	this.rules.push(new category_rule(this, cur));
      }
      cur=cur.nextSibling;
    }

    var list=this.tags.get("sub_categories")
    if(list) {
      list=split_semicolon(list);

      for(var i=0; i<list.length; i++)
	this.sub_categories.push("osm:"+list[i]);
    }

    this.write_div();

    if(this.open) {
      this.request_data();
    }

    // register overlay
    if(!(this.overlay=get_overlay(this.id)))
      this.overlay=new overlay(this.id);
    this.overlay.register_category(this);
    this.overlay.set_version(this.version);
  }

  // get_rule
  this.get_rule=function(id) {
    for(var i=0; i<this.rules.length; i++) {
      if(this.rules[i].id==id)
	return this.rules[i];
    }

    return null;
  }

  // new_rule
  this.new_rule=function() {
    if(!this.loaded) {
      alert("Not loaded yet!");
      return;
    }

    var el=new category_rule(this);
    this.rules.push(el);

    var div=document.createElement("div");
    this.div_rule_list.appendChild(div);
    div.className="editor_category_rule";

    el.editor(div, true);
  }

  // remove_rule
  this.remove_rule=function(rule) {
    if(!rule) {
      alert("category::remove_rule: no rule supplied");
      return null;
    }

    for(var i=0; i<this.rules.length; i++) {
      if(this.rules[i]==rule) {
        array_remove(this.rules, i);
      }
    }

    if(rule.div)
      rule.div.parentNode.removeChild(rule.div);
  }

  // constructor
  this.rules=[];
  this.load_def();
  this.result={ state: "no" };
}

function category_osm_init() {
  category_types["osm"]=category_osm;
}

register_hook("init", category_osm_init);
