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
    this.sub_categories.push(id);
    this.write_div();
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
  var x=document.getElementById("details_content");

  category_root.tags.set("name", t("list_info"));
  dom_clean(x);
  var div=dom_create_append(x, "div");

  category_root.attach_div(div);
  category_root.open_category(div);
}

register_hook("init", category_list_init);
