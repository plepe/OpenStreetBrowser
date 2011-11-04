var categories={};
var category_types={};

function get_category(id, param) {
  var x=id.split(/:/);

  if(!category_types[x[0]]) {
    alert("category type "+x[0]+" unknown!");
    return;
  }

  var sub_id="";
  if(param)
    sub_id=json_encode(param);

  if(!categories[id])
    categories[id]={};

  if(categories[id][sub_id])
    return categories[id][sub_id];

  var ob=new category_types[x[0]](x[1], param);
  categories[id][sub_id]=ob;

  return ob;
}

function category(id) {
  // request_data - request data in current view box
  this.request_data=function() {
  }

  // toggle_open - after clicking on category open category
  this.toggle_open=function(div) {
    if(div.open) {
      this.close_category(div);
    }
    else {
      this.open_category(div);
    }
  }

  // open_category - call to open category
  this.open_category=function(div) {
    div.className="category_open";
    div.open=true;

    this.write_div(div);
    this.request_data();

    this.unhide_category(div);

    update_permalink();
  }

  // close_category - call to close category
  this.close_category=function(div) {
    this.hide_category(div);

    div.className="category_closed";
    div.open=false;

    update_permalink();
  }

  // hide_category - when closing a this or a parent category, don't close
  //   this category too, but at least hide it
  this.hide_category=function(div) {
    if(!div.open)
      return;

    if(this.on_hide_category)
      this.on_hide_category(div);

    for(var i=0; i<this.sub_categories.length; i++) {
      var id=this.sub_categories[i].id;
      if(id&&div.sub&&div.sub.child_divs&&div.sub.child_divs[id])
	this.sub_categories[i].hide_category(div.sub.child_divs[id]);
    }

    call_hooks("hide_category", null, this);
  }

  // unhide_category - when opening a this or a parent category, and this
  //   category was opened before, show it again
  this.unhide_category=function(div) {
    if(!div.open)
      return;

    if(this.on_unhide_category)
      this.on_unhide_category(div);

    for(var i=0; i<this.sub_categories.length; i++) {
      var id=this.sub_categories[i].id;
      if(id&&div.sub&&div.sub.child_divs&&div.sub.child_divs[id])
	this.sub_categories[i].unhide_category(div.sub.child_divs[id]);
    }

    call_hooks("unhide_category", null, this);
  }

  // visible - is at least one instance of this category opened?
  this.visible=function() {
    for(var i=0; i<this.divs.length; i++)
      if(this.divs[i].open)
	return true;

    return false;
  }

  // visible_list - return recursively all visible sub categories
  this.visible_list=function() {
    var ret={};

    if(!this.visible())
      return 0;

    for(var i=0; i<this.sub_categories.length; i++) {
      var state=this.sub_categories[i].visible_list();
      if(state)
        ret[this.sub_categories[i].id]=state;
    }

    return ret;
  }

  // open_list - open categories according to list
  this.open_list=function(list) {
    for(var l in list) {
      var found=false;

      for(var i=0; i<this.sub_categories.length; i++) {
        if(this.sub_categories[i].id==l) {
          found=this.sub_categories[i];
        }
      }

      if(!found) {
        found=get_category("osm:"+l);
        this.register_sub_category(found);
        found.attach_div(this.divs[0].sub);
      }

      found.open_category(this.divs[0].sub.child_divs[l]);
      found.open_list(list[l]);
    }
  }

  // request_data - load new data from server
  this.request_data=function() {
  }

  // request_data_callback - called after loading new data from server
  this.request_data_callback=function(response) {
  }

  // recv - called after combined loading new data
  this.recv=function(data) {
  }

  // shall_reload - shall we load data combined from server?
  this.shall_reload=function(list, parent_div, viewbox) {
  }

  // load_def - load definition
  this.load_def=function() {
    // the default category is loaded instantly ... please adapt this
    // in the real category
    this.loaded=true;
    call_hooks("category_loaded", this);
  }

  // detach_div - remove category from div again
  this.detach_div=function(div) {
    for(var i=0; i<this.divs.length; i++) {
      if(this.divs[i]==div) {
	array_remove(this.divs, i);
	i--;
      }
    }
  }

  // write_status - write the status information of the category
  this.write_status=function(div) {
    dom_clean(div.status);

    if((!this.result)||(!this.result.status))
      return;

    switch(this.result.status) {
      case "loading":
	div.more.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+t("loading");
	break;
      case "recv":
        break;
      default:
        dom_create_append_text(div.status, t("category:status")+": "+this.result.status);
    }
  }

  // write_div - fill information of an attached div (or all) with data
  this.write_div=function(div) {
    if(!div) {
      for(var i=0; i<this.divs.length; i++) {
	this.write_div(this.divs[i]);
      }
      return;
    }

    dom_clean(div.header);
    div.header_name=dom_create_append(div.header, "a");
    div.header_name.href="#";
    div.header_name.className="header";
    div.header_name.onclick=this.toggle_open.bind(this, div);
    var name=lang_parse(this.tags.get_lang("name", ui_lang)));
    if(!name)
      name=lang("unnamed");
    dom_create_append_text(div.header_name, name);

    if(div.open) {
      this.write_status(div);

      dom_clean(div.sub);
      for(var i=0; i<this.sub_categories.length; i++) {
	if(typeof this.sub_categories[i]=="string")
	  this.sub_categories[i]=get_category(this.sub_categories[i]);

	this.sub_categories[i].attach_div(div.sub);
      }
    }

    call_hooks("category_write_div", div, this);
  }

  // attach_div - show category in a div
  this.attach_div=function(parent_div) {
    for(var i=0; i<this.divs.length; i++)
      if(this.divs[i].parent_div==parent_div) {
	parent_div.appendChild(this.divs[i]);
	this.write_div(this.divs[i]);
	return this.divs[i];
      }

    var div=dom_create_append(parent_div, "div");
    this.divs.push(div);
    div.parent_div=parent_div;
    if(!parent_div.child_divs)
      parent_div.child_divs={};
    parent_div.child_divs[this.id]=div;

    div.open=false;
    div.className="category_closed";
    div.category=this;

    div.header=dom_create_append(div, "div");
    div.header.className="header";

    div.status=dom_create_append(div, "div");
    div.status.className="status";

    div.data=dom_create_append(div, "div");
    div.data.className="data";

    div.more=dom_create_append(div, "div");
    div.more.className="more";

    div.sub=dom_create_append(div, "div");
    div.sub.className="sub";
    div.sub.init=false;

    this.write_div(div);

    return div;
  }

  // register_sub_category
  this.register_sub_category=function(ob) {
    this.sub_categories.push(ob);
  }

  // constructor
  this.id=id;
  this.tags=new tags({ name: this.id });
  this.sub_categories=[];
  this.version=0;
  this.divs=[];
  this.loaded=false;

  call_hooks("category_construct", this);
}
