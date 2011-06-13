var my_maps_toolbox;
var my_maps_loaded=[];
var my_maps_current=null;
var my_maps_control;
var my_maps_win;

function my_maps_register(my_map) {
  my_maps_loaded.push(my_map);
}

function my_maps_set_active(my_map) {
  my_maps_current=my_map;

  location.hash="#"+my_map.id;
}

function my_maps_search_object(ret, id) {
  for(var i=0; i<my_maps_loaded.length; i++) {
    if(my_maps_loaded[i].id==id)
      ret.push(my_maps_loaded[i]);
  }
}

function my_maps_item(data, feature) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="my_maps_item";

  // data
  this.data=function() {
    var ret=this.tags.data();

    ret.geo=this.feature.geometry.toString();
    ret.style=css_style_to_string(this.feature.style);

    return ret;
  }

  // constructor
  if(!data)
    data={  };
  if(!data.id)
    data.id=uniqid();

  this.id=data.id;
  this.tags=new tags(data);
  this.map=null;
  if(!feature) {
    // create feature from tags
    var geo=new postgis(data.geo);
    this.feature=geo.geo()[0];
    this.feature.style=css_style_from_string(this.tags.get("style"));
    vector_layer.addFeatures([this.feature]);
  }
  else {
    this.feature=feature;
    this.feature.style={ strokeWidth: 2, strokeColor: '#ff0000' };
  }

  vector_layer.redraw();
}

function my_maps_map(data) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="my_maps_map";

  // add_item
  this.add_item=function(item) {
    this.items.push(item);
    item.map=this;
  }

  // data
  this.data=function() {
    var ret={};
    ret.data=this.tags.data();
    ret.items=[];
    for(var i=0; i<this.items.length; i++) {
      ret.items.push(this.items[i].data());
    }

    return ret;
  }

  // name
  this.name=function() {
    var ret;

    if(!(ret=this.tags.get_lang("name"))) {
      return this.id;
    }

    return ret;
  }

  // info
  this.info=function(chapters) {
    var div=document.createElement("div");
    div.className="my_maps_tags_editor";

    this.tags.editor(div);
    chapters.push({ head: lang("head:tags"), content: div });
  }

  // info_show
  this.info_show=function() {
    my_maps_control.activate();
  }

  // info_hide
  this.info_hide=function() {
    my_maps_control.deactivate();
  }

  // save
  this.save=function() {
    var data=this.data();

    ajax("my_maps_save", { id: this.id }, this.save_callback.bind(this), data);
  }

  // save_callback
  this.save_callback=function(ret) {
    if(!ret.return_value) {
      alert("not saved?");
    }
    else {
      alert("saved");
    }
  }

  // constructor
  if(!data)
    data={ data: { id: "my_maps_"+uniqid()}, items: [] };

  this.id=data.data.id;
  this.tags=new tags(data.data);
  this.items=[];
  for(var i=0; i<data.items.length; i++) {
    this.add_item(new my_maps_item(data.items[i]));
  }
}

function my_maps_load(id) {
  ajax("my_maps_load", { id: id }, my_maps_load_callback);
}

function my_maps_load_callback(ret) {
  my_map=new my_maps_map(ret.return_value);

  my_maps_register(my_map);
  my_maps_set_active(my_map);
}

function my_maps_add_feature(feature) {
  if(!my_maps_current) {
    alert("No active my_maps!");
    return;
  }

  my_maps_current.add_item(new my_maps_item(null, feature));
  my_maps_current.save();
}

function my_maps_list() {
  if(my_maps_win)
    return;

  ajax("my_maps_list", {}, my_maps_list_callback);

  my_maps_win=new win('my_maps');
  my_maps_win.list=dom_create_append(my_maps_win.content, "div");
  my_maps_win.list.innerHTML="<img class='loading' src='img/ajax_loader.gif'> loading";
  my_maps_win.list.className="my_maps_list";
  
  var but=dom_create_append(my_maps_win.content, "input");
  but.type="button";
  but.onclick=my_maps_win.close.bind(my_maps_win);
  but.value=lang("close");
}

function my_maps_list_callback(ret) {
  dom_clean(my_maps_win.list);

  for(var i=0; i<ret.return_value.length; i++) {
    var m=ret.return_value[i];

    var li=dom_create_append(my_maps_win.list, "li");
    var a=dom_create_append(li, "a");
    dom_create_append_text(a, (m.name?m.name:m.id));
    a.onclick=my_maps_list_load.bind(this, m.id);
  }
}

function my_maps_list_load(id) {
  my_maps_load(id);

  my_maps_win.close();
  my_maps_win=null;
}

function my_maps_new() {
  var my_map=new my_maps_map(null);

  my_maps_register(my_map);
  my_maps_set_active(my_map);
}

function my_maps_init() {
  // create toolbox
  my_maps_toolbox=new toolbox({
    icon: "plugins/my_maps/icon.png",
    icon_title: "my_maps",
    weight: 5
  });
  register_toolbox(my_maps_toolbox);

  // add a control to the map - to be (de)activated when toolbox is
  // (de)activated
  my_maps_control=new OpenLayers.Control.DrawFeature(vector_layer,
    OpenLayers.Handler.Path, {'featureAdded': my_maps_add_feature });
  map.addControl(my_maps_control);

  // populate toolbox
  var input=dom_create_append(my_maps_toolbox.content, "input");
  input.type="button";
  input.onclick=my_maps_new;
  input.value=lang("my_maps:new_map");

  var input=dom_create_append(my_maps_toolbox.content, "input");
  input.type="button";
  input.onclick=my_maps_list;
  input.value=lang("my_maps:load_map");
}

register_hook("init", my_maps_init);
register_hook("search_object", my_maps_search_object);
