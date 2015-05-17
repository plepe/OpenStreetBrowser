var category_root;
var root_div;
var category_request;
var default_categories;

function _category_list() {
  this.inheritFrom=category;
  this.inheritFrom("root");

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

    var list=[];
    call_hooks("category_list_more", list, this);

    dom_clean(div.more);
    list=weight_sort(list);

    for(var i=0; i<list.length; i++) {
      div.more.appendChild(list[i]);
    }
  }

  // shall_request_data
  this.shall_reload=function(dummy, parent_div, viewbox) {
    var div;

    if(parent_div)
      div=parent_div.child_divs[this.id];
    else
      div=root_div;

    if((!div)||(!div.open))
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

    call_hooks("list_receive", data);

    var request=data.getElementsByTagName("request");
    if(!request.length)
      return;

    request=request[0];
    var viewbox=request.getAttribute("viewbox");

    var cats=data.getElementsByTagName("category");
    for(var cati=0; cati<cats.length; cati++) {
      get_category("osm:"+cats[cati].getAttribute("id"), function(cat, viewbox, ob) {
        if(ob)
          ob.recv(cat, viewbox);
      }.bind(cats[cati], viewbox));
    }
  }

  // the root category is always visible
  this.visible=function() {
    return true;
  }

  // constructor
  register_hook("view_changed_delay", this.shall_reload.bind(this));
  this.tags.set("name", t("list_info"));
}

  // choose_category_callback
  _category_list.prototype.add_category=function(id) {
    this.sub_categories.push(id);
    this.write_div();
  }


function category_list_init() {
  category_root=new _category_list();

  if(default_categories) {
    for(var i=0; i<default_categories.length; i++) {
      category_root.sub_categories.push("osm:"+default_categories[i]);
    }
  }
}

function category_list_hash_changed(hash) {
  // Either show category list or something else (like details)
  var div=document.getElementById("details_content");

  dom_clean(div);
  root_div=category_root.attach_div(div);

  if((!hash)||(!hash.obj)) {
    category_root.open_category(root_div);
  }
  else {
    category_root.close_category(root_div);
  }

  // Check if a category is opened by permalink
  if(hash&&hash.categories) {
    var list=string_to_category_list(hash.categories);

    category_root.open_list(list);
  }
}

function category_list_to_string(hash) {
  if(!hash)
    return "";

  var list=[];
  for(var i in hash) {
    var x=category_list_to_string(hash[i]);

    var ret=i;
    if(x!="")
      ret+="["+x+"]";
    list.push(ret);
  }

  return list.join(",");
}

function string_to_category_list(str) {
  var level=0;
  var current_id="";
  var list={};
  var sub_text="";

  if(str=="")
    return {};

  for(var i=0; i<str.length; i++) {
    switch(str.substr(i, 1)) {
      case "[":
        if(level>0)
          sub_text+=str[i];
        level++;
        break;
      case "]":
        level--;
        if(level>0)
          sub_text+=str[i];
        break;
      case ",":
        if(level==0) {
          list[current_id]=string_to_category_list(sub_text);
          sub_text="";
          current_id="";
        }
        else
          sub_text+=str[i];
        break;
      default:
        if(level==0)
          current_id+=str[i];
        else if(level>0)
          sub_text+=str[i];
    }
  }

  if(current_id!="")
    list[current_id]=string_to_category_list(sub_text);

  return list;
}

function category_list_permalink(permalink) {
  var a=1;

  var ret=category_root.visible_list();
  ret=category_list_to_string(ret);

  permalink.categories=ret;
}

register_hook("init", category_list_init);
register_hook("hash_changed", category_list_hash_changed);
register_hook("get_permalink", category_list_permalink);
