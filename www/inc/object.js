var objects=[];
var tag_to_place_type={ "relation": "rel", "way": "way", "node": "node", "coll": "coll" };

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

function load_objects(id) {
  ajax("get_objects", { "obs": id, "all": 1 }, load_object);
}

function load_object(data) {
  var data=response.responseXML;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);
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
}

function place_way(data, obj) {
  this.inheritFrom=place;
  this.inheritFrom(data, obj);

  this.load_members=function() {
    if(this.members)
      return;

//    this.members=[];
//    var mems=this.data.getElementsByTagName("nd");
//    for(var i=0; i<mems.length; i++) {
//      var el=get_loaded_object("node_"+mems[i].getAttribute("ref"));
//      if(el) {
//	this.members.push(el);
//	el.load_members();
//      }
//    }
  }

  this.get_geo=function() {
    if(this.geo)
      return this.geo;

    this.way=new postgis(this.data.getAttribute("way"));
    this.geo=this.way.geo();

    return this.geo;
  }

  this.get_highlight=function() {
    if(this.highlight)
      return this.highlight;

    this.highlight=[];

    var linestring=this.get_geo();

    for(var i=0; i<linestring.length; i++) {
      var vector=new OpenLayers.Feature.Vector(linestring[i].clone(), 0, {
	externalGraphic: "img/hi_node.png",
	graphicWidth: 25,
	graphicHeight: 25,
	graphicXOffset: -13,
	graphicYOffset: -13,
	fill: "none",
	strokeWidth: 4,
	strokeColor: "black"
      });
      this.highlight.push(vector);
    }

    return this.highlight;
  }

  this.get_display_features=function() {
    if(this.display_features)
      return this.display_features;

    this.display_features=[];

    var linestring=this.get_geo();

    for(var i=0; i<linestring.length; i++) {
      var vector=new OpenLayers.Feature.Vector(linestring[i].clone(), 0, {
	strokeWidth: 2,
	strokeColor: "black",
	externalGraphic: "img/big_node.png",
	graphicWidth: 11,
	graphicHeight: 11,
	graphicXOffset: -6,
	graphicYOffset: -6,
	fill: "none"
      });
      this.display_features.push(vector);
    }

    return this.display_features;
  }

}

function place_node(data, obj) {
  this.inheritFrom=place;
  this.inheritFrom(data, obj);

  this.get_geo=function() {
    if(this.geo)
      return this.geo;

    this.way=new postgis(this.data.getAttribute("way"));
    this.geo=this.way.geo();

    return this.geo;
  }

  this.get_highlight=function() {
    if(this.highlight)
      return this.highlight;

    var p=this.get_geo();

    this.highlight=[];
    for(var i=0; i<p.length; i++) {
      this.highlight.push(new OpenLayers.Feature.Vector(p[i].clone(), 0, {
	strokeWidth: 4,
	strokeColor: "black",
	externalGraphic: "img/hi_node.png",
	graphicWidth: 25,
	graphicHeight: 25,
	graphicXOffset: -13,
	graphicYOffset: -13,
	fill: "none"
      }));
    }

    return this.highlight;
  }

  this.get_display_features=function() {
    if(this.display_features)
      return this.display_features;

    var p=this.get_geo();

    this.display_features=[];
    for(var i=0; i<p.length; i++) {
      this.display_features.push(new OpenLayers.Feature.Vector(p[i].clone(), 0, {
	strokeWidth: 2,
	strokeColor: "black",
	externalGraphic: "img/big_node.png",
	graphicWidth: 11,
	graphicHeight: 11,
	graphicXOffset: -6,
	graphicYOffset: -6,
	fill: "none"
      }));
    }

    return this.display_features;
  }
}

function place_rel(data, obj) {
  this.inheritFrom=place;
  this.inheritFrom(data, obj);

  this.get_highlight=function() {
    this.load_members();
    this.highlight=[];

    for(var i=0; i<this.members.length; i++) {
      var el=this.members[i];
      var role=this.members_roles[i];

      if(el) {
	var p=el.place.get_geo();
	if(p)
	  for(var j=0; j<p.length; j++) {
	    var vector=new OpenLayers.Feature.Vector(p[j].clone(), 0, {
	      strokeWidth: 4,
	      strokeColor: "black",
	      externalGraphic: "img/big_node.png",
	      graphicWidth: 11,
	      graphicHeight: 11,
	      graphicXOffset: -6,
	      graphicYOffset: -6,
	      fill: "none"
	    });
	    this.highlight.push(vector);
	  }
      }
    }

    return this.highlight;
  }

  this.get_display_features=function() {
    this.load_members();
    this.display_features=[];

    for(var i=0; i<this.members.length; i++) {
      var el=this.members[i];
      var role=this.members_roles[i];

      if(el) {
	var p=el.place.get_geo();
	if(p)
	  for(var j=0; j<p.length; j++) {
	    var vector=new OpenLayers.Feature.Vector(p[j].clone(), 0, {
	      strokeWidth: 2,
	      strokeColor: "black",
	      externalGraphic: "img/node.png",
	      graphicWidth: 7,
	      graphicHeight: 7,
	      graphicXOffset: -4,
	      graphicYOffset: -4,
	      fill: "none"
	    });
	    this.display_features.push(vector);
	  }
      }
    }

    return this.display_features;
  }

  this.load_members=function() {
    if(!this.members) {
      this.members=[];
      this.members_roles=[];
    }

    var mems=this.data.getElementsByTagName("member");
    for(var i=0; i<mems.length; i++) {
      if(!this.members[i]) {
	var el=get_loaded_object(mems[i].getAttribute("type")+"_"+mems[i].getAttribute("ref"));
	this.members[i]=el;
	if(el) {
	  el.load_members();
	}
      }
    }
  }
}

function place_coll(data, obj) {
  this.inheritFrom=place;
  this.inheritFrom(data, obj);

  this.get_highlight=function() {
    this.load_members();
    this.highlight=[];

    for(var i=0; i<this.members.length; i++) {
      var el=this.members[i];
      //var role=this.members_roles[i];

      if(el) {
	var p=el.place.get_geo();
	if(p)
	  for(var j=0; j<p.length; j++) {
	    var vector=new OpenLayers.Feature.Vector(p[j].clone(), 0, {
	      strokeWidth: 4,
	      strokeColor: "black",
	      externalGraphic: "img/big_node.png",
	      graphicWidth: 11,
	      graphicHeight: 11,
	      graphicXOffset: -6,
	      graphicYOffset: -6,
	      fill: "none"
	    });
	    this.highlight.push(vector);
	  }
      }
    }

    return this.highlight;
  }

  this.get_display_features=function() {
    this.load_members();
    this.display_features=[];

    for(var i=0; i<this.members.length; i++) {
      var el=this.members[i];
//      var role=this.members_roles[i];

      if(el) {
	var p=el.place.get_geo();
	if(p)
	  for(var j=0; j<p.length; j++) {
	    var vector=new OpenLayers.Feature.Vector(p[j].clone(), 0, {
	      strokeWidth: 2,
	      strokeColor: "black",
	      externalGraphic: "img/node.png",
	      graphicWidth: 7,
	      graphicHeight: 7,
	      graphicXOffset: -4,
	      graphicYOffset: -4,
	      fill: "none"
	    });
	    this.display_features.push(vector);
	  }
      }
    }

    return this.display_features;
  }

  this.load_members=function() {
    this.members=[];
    var mems=this.data.getElementsByTagName("member");
    for(var i=0; i<mems.length; i++) {
      if(!this.members[i]) {
	var el=get_loaded_object(mems[i].getAttribute("type")+"_"+mems[i].getAttribute("ref"));
	this.members[i]=el;
	if(el) {
	  el.load_members();
	}
      }
    }
  }
}

function obj(data) {
//  this.inheritFrom=element;
//  this.inheritFrom(id, data);
  this.data=data;
  this.id=tag_to_place_type[data.tagName]+"_"+data.getAttribute("id");

  var tmp=new Function("data", "return new place_"+tag_to_place_type[data.tagName]+"(data);");
  this.place=tmp(data, this);

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


