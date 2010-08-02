function category_osm(id) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  // load_def
  this.load_def=function(version) {
    var param={ todo: "load" };
    param.id=this.id;

    if(version)
      param.version=version;

    ajax_direct("categories.php", param, this.load_def_callback.bind(this));
  }

  // load_callback
  this.load_def_callback=function(response) {
    var data=response.responseXML;
    var cat_data=data.firstChild;

    if(cat_data)
      this.tags.readDOM(cat_data);

    this.version=cat_data.getAttribute("version");
    this.rules=[];

    var cur=cat_data.firstChild;
    while(cur) {
      if(cur.nodeName=="rule") {
	this.rules.push(new category_rule(this, cur));
      }
      cur=cur.nextSibling;
    }

    var list=this.tags.get("sub_categories")
    if(list) {
      list=split_semicolon(list);

      for(var i=0; i<list.length; i++)
	this.sub_categories.push("osm:"+list[i]);
    }

    this.write_div();

    if(this.open) {
      this.request_data();
    }

    // register overlay
    if(!(this.overlay=get_overlay(this.id)))
      this.overlay=new overlay(this.id);
    this.overlay.register_category(this);
    this.overlay.set_version(this.version);
  }

  // constructor
  this.rules=[];
  this.load_def();
  this.result={ state: "no" };
}

function category_osm_init() {
  category_types["osm"]=category_osm;
}

register_hook("init", category_osm_init);
