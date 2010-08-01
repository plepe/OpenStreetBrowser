var categories={};

function category(id) {
  // request_data - request data in current view box
  this.request_data=function() {
  }

  // toggle_open - after clicking on category open category
  this.toggle_open=function(div) {
    div.open=!div.open;
    div.className="category_"+(div.open?"open":"closed");

    if(div.open) {
      this.write_div();
      this.request_data();
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
  this.shall_reload=function() {
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

      if(!this.sub_categories) {
	this.sub_categories=[];
	this.load_sub_categories();
      }

      if(!div.sub.init) {
	div.sub.init=true;

	for(var i=0; i<this.sub_categories.length; i++) {
	  var d=dom_create_append(div.sub, "div");
	  this.sub_categories[i].attach_div(d);
	}
      }
    }
  }

  // attach_div - show category in a div
  this.attach_div=function(div) {
    this.divs.push(div);
    div.open=false;
    div.className="category_closed";
    div.category=this;

    div.header=dom_create_append(div, "div");
    div.header.className="header";
    div.header.onclick=this.toggle_open.bind(this, div);

    div.status=dom_create_append(div, "div");
    div.status.className="status";

    div.sub=dom_create_append(div, "div");
    div.sub.className="sub";
    div.sub.init=false;

    div.data=dom_create_append(div, "div");
    div.data.className="data";

    div.more=dom_create_append(div, "div");
    div.more.className="more";

    this.write_div(div);
  }

  // load_sub_categories
  this.load_sub_categories=function() {
  }

  // constructor
  this.id=id;
  this.tags=new tags({ name: this.id });
  this.sub_categories=0;
  this.version=0;
  this.divs=[];
  this.loaded=false;
}
