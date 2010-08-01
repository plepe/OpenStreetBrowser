var category_root;

function _category_list() {
  this.inheritFrom=category;
  this.inheritFrom("root");
  
  // write_div
  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;

    dom_clean(div.more);
    var more_cat=dom_create_append_text(div.more, "More categories");
    //more_cat.onclick=;
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
  this.open=true;
  register_hook("viewbox_change", this.shall_reload.bind(this));
}

function category_list_init() {
  category_root=new _category_list();
  var ob=document.getElementById("details_content");

  category_root.attach_div(ob);
}

register_hook("init", category_list_init);
