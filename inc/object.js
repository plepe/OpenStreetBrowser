/// maybe deprecated code following
var objects=[];
var tag_to_place_type={ "relation": "rel", "way": "way", "node": "node", "coll": "coll" };

function set_feature_style(highlights, style) {
  for(var i=0; i<highlights.length; i++) {
    highlights[i].style=style;
  }
}

function load_objects_from_xml(src) {
  if(!src)
    return;

  for(var i=0; i<src.length; i++) {
    var el=src[i].firstChild;
    while(el) {
      register_object(el);
   
      el=el.nextSibling;
    }
  }
}

function get_loaded_object(id) {
  if(id=="")
    return null;

  if(objects[id])
    return objects[id];

  return null;
}

function register_object(data) {
  var id=tag_to_place_type[data.tagName]+"_"+data.getAttribute("id");

  if(objects[id])
    return objects[id];

  var el=new obj(data);
  objects[id]=el;

  return el;
}

function place(data, obj) {
  this.data=data;
  this.obj=obj;
  this.highlight=0;

  this.load_members=function() {}

  this.get_highlight=function() {
    if(!this.highlight)
      this.highlight=[];
    return this.highlight;
  }

  this.get_tag=function(key) {
    var ob=this.data.getElementsByTagName("tag");
    for(var i=0; i<ob.length; i++) {
      if(ob[i].getAttribute('k')==key)
	return ob[i].getAttribute('v');
    }

    return null;
  }

  this.get_geo=function() {
    if(this.geo)
      return this.geo;

    this.geo=wkt_to_features(this.data.getAttribute("way"));

    return this.geo;
  }

  this.get_highlight=function() {
    if(this.highlight)
      return this.highlight;

    this.highlight=this.get_geo();
    set_feature_style(this.highlight, 
      {
	strokeWidth: 4,
	strokeColor: "black",
	externalGraphic: "img/hi_node.png",
	graphicWidth: 25,
	graphicHeight: 25,
	graphicXOffset: -13,
	graphicYOffset: -13,
	fill: "none"
      });

    return this.highlight;
  }

  this.get_display_features=function() {
    if(this.display_features)
      return this.display_features;

    this.display_features=this.get_geo();
    set_feature_style(this.display_features,
      {
	strokeWidth: 2,
	strokeColor: "black",
	externalGraphic: "img/big_node.png",
	graphicWidth: 11,
	graphicHeight: 11,
	graphicXOffset: -6,
	graphicYOffset: -6,
	fill: "none"
      });

    return this.display_features;
  }
}

function obj(data) {
//  this.inheritFrom=element;
//  this.inheritFrom(id, data);
  this.data=data;
  this.id=tag_to_place_type[data.tagName]+"_"+data.getAttribute("id");

  this.place=new place(data, this);

  this.type=function() {
    return "default";
  }

  this.get_display_features=function() {
    if(!this.place.display_features)
      this.place.get_display_features();

    for(var i=0; i<this.place.display_features.length; i++) {
      vector_layer.addFeatures(this.place.display_features[i]);
      shown_features.push(this.place.display_features[i]);
    }
  }

  this.display=function() {
    return this.get_display_features();
  }

  this.load_members=function() {
    return this.place.load_members();
  }

  this.get_highlight=function() {
    return this.place.get_highlight();
  }
}


