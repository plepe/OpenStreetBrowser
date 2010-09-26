function category_rule_match(dom, cat, rule) {
  this.tags=new tags();
  this.tags.readDOM(dom);
  this.category=cat;
  this.rule=rule;
  this.id=dom.getAttribute("id");
  this.id_split=split_semicolon(dom.getAttribute("id"));

  // text
  this.write_list=function(ul) {
    var x;
    var name="";
    var add="";
    var title="";

    var li=dom_create_append(ul, "li");
    li.id=this.id;
    li.rule_id=this.rule.id;
    if(x=this.tags.get("icon")) {
      li.style.listStyleImage="url('"+x+"')";
    }
    
    if(this.rule.tags.get_lang("name", ui_lang)) {
      title=split_semicolon(this.rule.tags.get_lang("name", ui_lang));
      if(title.length==1)
	title=title[0];
      else {
	if(this.id_split.length>1)
	  title=title[1];
	else
	  title=title[0];
      }
    }
    else
      title=this.rule.tags.get("match");
    li.title=title;

    var a=dom_create_append(li, "a");
    a.href="#"+this.id;
    a.onmouseover=this.set_highlight.bind(this);
    a.onmouseout=this.unset_highlight.bind(this);

    x=this.tags.get("display_name");
    if(!x)
      x=t("unnamed");
    dom_create_append_text(a, x);
    
    if(x=this.tags.get("display_type")) {
      x=" ("+x+")";
      dom_create_append_text(li, x);
    }
  }
  
  // load_more_data
  this.load_more_tags=function(tags, callback) {
    var param={};
    param.id=this.id;
    param.tags=tags.join(",");

    ajax("object_load_more_tags", param, this.load_more_tags_callback.bind(this, callback));
  }

  // load_more_tags_callback
  this.load_more_tags_callback=function(callback, response) {
    if(!response.return_value) {
      alert("response not valid: "+response.responseText);
      return;
    }

    for(var i in response.return_value)
      this.tags.set(i, response.return_value[i]);

    callback(response.return_value);
  }

  // highlight_geo
  this.highlight_geo=function(param) {
    if(this.highlight) {
      if(this.tags.get("geo"))
	this.highlight.add_geo([this.tags.get("geo")]);
    }
  }

  // set_highlight
  this.set_highlight=function() {
    var geos=[];

    if(!this.highlight) {
      var geo=this.tags.get("geo");
      var geo_center=this.tags.get("geo:center");

      if(!geo) {
	// request from server
	this.load_more_tags(["geo"], this.highlight_geo.bind(this));
      }
      else {
	geos.push(geo);
      }
      if(!geo_center) {
	geo_center=geo;
      }

      this.highlight=new highlight(geos, geo_center);
    }

    this.highlight.show();
  }

  // unset_highlight
  this.unset_highlight=function() {
    if(!this.highlight)
      return;

    this.highlight.hide();
  }
}

function category_rule(category, dom) {
  // constructor
  this.icon=null;
  if(!dom) {
    this.id=uniqid();
    this.tags=new tags(category_rule_tags_default);
  }
  else {
    this.id=dom.getAttribute("id");
    this.tags=new tags();
    this.tags.readDOM(dom);
//    if(this.tags.get("icon"))
//      this.icon=icon_git.get_obj(this.tags.get("icon"));
// TODO: maybe initialize icon, when we need it?
  }
  this.data=[];
  this.category=category;

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
