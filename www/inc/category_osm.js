var importance=[ "global", "international", "national", "regional", "urban", "suburban", "local" ];
var category_tags_default=
  { "name": "", "description": "", "lang": "en", "sub_categories": "", "id": "" };
var category_rule_tags_default=
  { "name": "", "match": "", "description": "", "icon": "", 
    "importance": "urban", "type": "polygon;point" };

function category_osm(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  // shall_reload
  this.shall_reload=function(list, parent_div, viewbox) {
    var div=parent_div.child_divs[this.id];

    if(this.result&&this.result.viewbox==viewbox)
      return;

    if(!div.open)
      return;

    if((this.rules.length)&&(!list[this.id])) {
      list[this.id]=true;
      this.result=new this.result_ob(this);
      this.result.status="loading";
      this.write_div();
    }

    for(var i=0; i<this.sub_categories.length; i++) {
      this.sub_categories[i].shall_reload(list, div.sub, viewbox);
    }
  }

  // unhide_category
  this.on_unhide_category=function(div) {
    if(this.overlay)
      this.overlay.show();
  }

  // hide_category
  this.on_hide_category=function(div) {
    if(this.overlay)
      this.overlay.hide();
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

    if((this.result.status=="recv")&&(!this.result.data.length)) {
      var txt=dom_create_append_text(div.data, t("nothing found"));
    }
    else {
      var ul=dom_create_append(div.data, "ul");

      for(var i=offset; i<max; i++) {
	var match_ob=this.result.data[i];
	call_hooks("category_show_match", this, match_ob);
	match_ob.write_list(ul);
      }
    }

    dom_clean(div.more);
    if(!this.result.complete) {
      var a=dom_create_append(div.more, "a");
      a.href="#";
      a.onclick=this.show_more.bind(this);
      dom_create_append_text(a, t("more"));
    }

    this.write_status(div);
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

    if(!this.rules.length)
      return;

    if((this.result)&&(this.result.viewbox==param.viewbox)&&(!more)) {
      this.write_div();
      return;
    }
    if((!more)||(!this.result)) {
      this.result=new this.result_ob(this);
    }

    this.result.status="loading";
    this.write_div();

    var there=this.result.get_ids();
    if(there.length) {
      param.exclude=there.join(",");
    }

    ajax_direct("list.php", param, this.request_data_callback.bind(this));
  }

  // request_data_callback - called after loading new data from server
  this.request_data_callback=function(response) {
    var data=response.responseXML;

    if(!data) {
      alert("no data\n"+response.responseText);
      return;
    }

    call_hooks("list_receive", data);

    var request=data.getElementsByTagName("request");
    if(!request.length)
      return;

    request=request[0];
    var viewbox=request.getAttribute("viewbox");

    var cats=data.getElementsByTagName("category");
    for(var cati=0; cati<cats.length; cati++) {
      this.recv(cats[cati], viewbox);
    }
  }

  // write_status - write the status information of the category
  this.inherit_write_status=this.write_status;
  this.write_status=function(div) {
    this.inherit_write_status(div);

    if((!this.result)||(!this.result.data_status))
      return;

    if(div.status.firstChild)
      dom_create_append(div.status, "br");

    switch(this.result.data_status) {
      case "old_version":
      case "not_compiled":
	var span=dom_create_append(div.status, "span");
	span.className="category_old_version";
	dom_create_append_text(span, t("category:"+this.result.data_status));
        break;
      default:
	dom_create_append_text(div.status, t("category:data_status")+": "+this.result.data_status);
        break;
    }
  }

  // result_ob - an object in category_osm for the current result
  this.result_ob=function(category) {
    // recv
    this.recv=function(dom, viewbox) {
      this.version=dom.getAttribute("version");
      this.complete=dom.getAttribute("complete")=="true";
      this.data_status=dom.getAttribute("status");

      // maybe we can save it to the cache if the viewbox changed?
      if((this.viewbox)&&(this.viewbox!=viewbox)) {
	return;
      }
      this.viewbox=viewbox;

      var matches=dom.getElementsByTagName("match");
      var last_importance="";

      for(var mi=0; mi<matches.length; mi++) {
	var match=matches[mi];
	var rule=category.get_rule(match.getAttribute("rule_id"));
	if(rule) {
	  var match_ob=rule.load_entry(match);

	  this.data.push(match_ob);
	  call_hooks("category_load_match", this, match_ob)

	  var mimp=match_ob.tags.get("#importance");
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

    // Got a wrong version - reload definition (which requests new data later)
    var recv_version=dom.getAttribute("version");
    if((recv_version)&&(recv_version!=this.version)) {
      // save received data to be processed after loading correct version
      this.recv_pending=[ dom, viewbox, this.result ];
      this.result=0;

      this.load_def(recv_version);
      return;
    }

    if(!this.result) {
      alert("Result object vanished");
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

      for(var i=0; i<list.length; i++) {
        var found=false;

        for(var j=0; j<this.sub_categories.length; j++) {
          if(((typeof this.sub_categories[j]=="string")&&
               (this.sub_categories[j]==list[i]))||
             ((typeof this.sub_categories[j]=="object")&&
               (this.sub_categories[j].id==list[i]))) {
            found=true;
          }
        }

        if(!found)
          this.sub_categories.push("osm:"+list[i]);
      }
    }

    this.write_div();

    if(this.rules.length) {
      // register overlay - empty categories don't get an overlay
      if(this.tags.get("no_overlay")=="yes")
	delete(this.overlay);
      else if(!(this.overlay=get_overlay(this.id)))
	this.overlay=new overlay(this.id);

      if(this.overlay) {
	this.overlay.register_category(this);
	this.overlay.set_version(this.version);
	this.overlay.set_name(this.tags.get_lang("name", ui_lang));
      }
    }

    // We still have received data ... process now
    if(this.recv_pending) {
      var x=this.recv_pending;
      delete(this.recv_pending);
      this.result=x[2];
      this.recv(x[0], x[1]);
    }
    // if not, we could load data now
    else if(category_root)
      category_root.shall_reload();

    if(this.visible()) {
      this.on_unhide_category();
    }

    this.loaded=true;
    call_hooks("category_loaded", this);
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
