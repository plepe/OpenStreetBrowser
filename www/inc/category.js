var categories={};
var category_types={};

function get_category(id) {
  var x=id.split(/:/);

  if(!category_types[x[0]]) {
    alert("category type "+x[0]+" unknown!");
    return;
  }

  if(categories[id])
    return categories[id];

  var ob=new category_types[x[0]](x[1]);
  categories[id]=ob;
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
  }

  // close_category - call to close category
  this.close_category=function(div) {
    div.className="category_closed";
    div.open=false;
  }

  // visible - is at least one instance of this category opened?
  this.visible=function() {
    for(var i=0; i<this.divs.length; i++)
      if(this.divs[i].open)
	return true;

    return false;
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
    this.loaded=true;
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

  // write_div - fill information of an attached div (or all) with data
  this.write_div=function(div) {
    if(!div) {
      for(var i=0; i<this.divs.length; i++) {
	this.write_div(this.divs[i]);
      }
      return;
    }

    dom_clean(div.header);
    dom_create_append_text(div.header, this.tags.get_lang("name", ui_lang));

    if(div.open) {
      dom_clean(div.status);
      if(this.result&&this.result.status)
	dom_create_append_text(div.status, t("category:"+this.result.status));

      dom_clean(div.sub);
      for(var i=0; i<this.sub_categories.length; i++) {
	if(typeof this.sub_categories[i]=="string")
	  this.sub_categories[i]=get_category(this.sub_categories[i]);

	this.sub_categories[i].attach_div(div.sub);
      }
    }
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
    div.header.onclick=this.toggle_open.bind(this, div);

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

  // constructor
  this.id=id;
  this.tags=new tags({ name: this.id });
  this.sub_categories=[];
  this.version=0;
  this.divs=[];
  this.loaded=false;
}
