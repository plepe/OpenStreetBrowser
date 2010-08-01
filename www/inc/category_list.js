var category_root;

function _category_list() {
  this.inheritFrom=category;
  this.inheritFrom("root");

  // choose_category
  this.choose_category=function() {
    new category_chooser(this.choose_category_callback.bind(this));
  }

  // choose_category_callback
  this.choose_category_callback=function(id) {
    var ob=categories[id];
    if(!ob)
      ob=new category_osm(id);

    this.sub_categories.push(ob);

    for(var i=0; i<this.divs.length; i++) {
      if(!this.divs[i].sub.init)
	continue;

      var d=dom_create_append(this.divs[i].sub, "div");
      ob.attach_div(d);
    }

    ob.write_div();
  }
  
  // write_div
  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;

    dom_clean(div.more);
    var more_cat=dom_create_append(div.more, "a");
    dom_create_append_text(more_cat, "More categories");
    more_cat.onclick=this.choose_category.bind(this);
  }

  // shall_request_data
  this.shall_reload=function(dummy, viewbox) {
    var list={};

    for(var i=0; i<this.sub_categories.length; i++) {
      this.sub_categories[i].shall_reload(list, viewbox);
    }

    alert(print_r(list));
  }

  // constructor
  register_hook("viewbox_change", this.shall_reload.bind(this));
}

function category_list_init() {
  category_root=new _category_list();
  var div=document.getElementById("details_content");

  category_root.attach_div(div);
  category_root.toggle_open(div);
}

register_hook("init", category_list_init);
