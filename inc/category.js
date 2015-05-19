var categories={};
var get_category_requests = {};
var category_types={};

function call_category_requests(id) {
  var ob = categories[id];

  if(ob.is_loaded) {
    for(var i = 0; i < get_category_requests[id].length; i++)
      get_category_requests[id][i](ob);

    delete(get_category_requests[i]);
  }
  else
    ob.once('load', function(id) {
      var ob = categories[id];

      for(var i = 0; i < get_category_requests[id].length; i++)
        get_category_requests[i](ob);

      delete(get_category_requests[i]);
    }.bind(this, id));
}

function get_category(id, callback) {
  if((!callback) || (typeof callback != "function")) {
    alert("get_category(" + id + ") - no callback supplied!");
    return;
  }

  if(categories[id]) {
    callback(categories[id]);
    return;
  }

  // there's already an active request -> add current request and wait
  if(id in get_category_requests) {
    get_category_requests[id].push(callback);
    return;
  }

  get_category_requests[id] = [ callback ];

  // if id has several parts (separated by /), iterate to the root category and
  // request its data - which should contain data for sub categories
  var id_parts = id.split("/");
  if(id_parts.length == 1) {
    ajax_json("get_category", { id: id }, function(id, data) {
      if(!data) {
        alert("get_category(" + id + "): no such category");
        return;
      }

      if(!data.type) {
        alert("get_category(" + id + "): cannot parse result");
        return;
      }

      if(typeof window[data.type] != 'function') {
        alert("get_category(" + id + "): unknown category type " + data.type);
        return;
      }

      var ob = new window[data.type](id, data);
      categories[id] = ob;
      call_category_requests(id);

    }.bind(this, id));
  }
  else {
    get_category(id_parts.slice(0, -1).join('/'), function(id, ob) {
      if(!ob) {
        alert("get_category(" + id + "): no such category");
        return;
      }

      if(typeof ob.get_category != "function") {
        alert("get_category(" + id + "): category does not have sub categories");
        return;
      }

      ob.get_category(id, function(id, ob) {
        if(!ob) {
          alert("get_category(" + id + "): no such category");
          return;
        }

        categories[id] = ob;
        call_category_requests(id);

      }.bind(this, id));
    }.bind(this, id));
  }
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

    return false;
  }

  // open_category - call to open category
  this.open_category=function(div) {
    if(!div)
      return;

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
      if(this.sub_categories[i].visible_list) {
	var state=this.sub_categories[i].visible_list();
	if(state)
	  ret[this.sub_categories[i].id]=state;
      }
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
        get_category("osm:"+l, function(l, found) {
          this.register_sub_category(found);
          found.attach_div(this.divs[0].sub);

          found.open_category(this.divs[0].sub.child_divs[l]);
          found.open_list(list[l]);
        }.bind(this, l));
      }
      else {
        found.open_category(this.divs[0].sub.child_divs[l]);
        found.open_list(list[l]);
      }
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
    div.header_name.className="header";
    div.header_name.onclick=this.toggle_open.bind(this, div);
    var name=lang_parse(this.tags.get_lang("name", ui_lang));
    if(!name)
      name=lang("unnamed");
    dom_create_append_text(div.header_name, name);

    if(div.open) {
      this.write_status(div);

      dom_clean(div.sub);
      for(var i=0; i<this.sub_categories.length; i++) {
	if(typeof this.sub_categories[i]=="string") {
	  get_category(this.sub_categories[i], function(i, div, ob) {
            this.sub_categories[i] = ob;

            this.sub_categories[i].attach_div(div.sub);
          }.bind(this, i, div));
        }
        else
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
    this.write_div();
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

register_hook("search_object", function(ret, id, callback) {
  var id_parts = id.split("/");

  if(id_parts.length == 1)
    return;

  var category_id = id_parts.slice(0, id_parts.length - 1).join("/");
  var object_id = id_parts[id_parts.length - 1];

  get_category(category_id, function(object_id, callback, category) {
    if((category === null) || (!category.search_object)){
      callback(null);
      return;
    }

    category.search_object(object_id, function(category, callback, ob) {
      category_root.register_sub_category(category);
      callback(ob);
    }.bind(this, category, callback));
  }.bind(this, object_id, callback));

  ret.push(null);
});
