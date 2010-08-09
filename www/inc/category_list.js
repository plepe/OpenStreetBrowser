var category_root;
var root_div;
var category_request;

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
  
  // attach_div
  this.inherit_attach_div=this.attach_div;
  this.attach_div=function(parent_div) {
    var div=this.inherit_attach_div(parent_div);

    div.appendChild(div.more);

    return div;
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
  this.shall_reload=function(dummy, parent_div, viewbox) {
    var div;

    if(parent_div)
      div=parent_div.child_divs[this.id];
    else
      div=root_div;

    if(!div.open)
      return;

    var list={};
    var viewbox=get_viewbox();

    for(var i=0; i<this.sub_categories.length; i++) {
      this.sub_categories[i].shall_reload(list, div.sub, viewbox);
    }

    if(!keys(list).length)
      return;

    var param={};
    param.viewbox=get_viewbox();
    param.zoom=get_zoom();
    param.category=keys(list).join(",");
    param.count=10;

    if(category_request) {
      category_request.abort();
    }

    category_request=ajax_direct("list.php", param, this.request_data_callback.bind(this));
  }

  // request_data_callback - called after loading new data from server
  // TODO: OSM-specific, this might not be the correct place
  this.request_data_callback=function(response) {
    var data=response.responseXML;
    category_request=null;

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
      var ob=get_category("osm:"+cats[cati].getAttribute("id"));
      if(ob)
	ob.recv(cats[cati], viewbox);
    }
  }

  // constructor
  register_hook("view_changed_delay", this.shall_reload.bind(this));
  this.tags.set("name", t("list_info"));
}

function category_list_init() {
  category_root=new _category_list();
}

function category_list_hash_changed(hash) {
  var div=document.getElementById("details_content");

  dom_clean(div);
  root_div=category_root.attach_div(div);

  if(hash=="") {
    category_root.open_category(root_div);
  }
  else {
    category_root.close_category(root_div);
  }
}

register_hook("init", category_list_init);
register_hook("hash_changed", category_list_hash_changed);
