var osm_object_valid_prefixes=[ "node", "way", "rel" ];

function osm_object(dom) {
  this.inheritFrom=geo_object;
  this.inheritFrom();

  // load_more_data
  this.load_more_tags=function(tags, callback, dom_ob) {
    var param={};
    param.id=this.id;
    param.tags=tags.join(",");

    if(dom_ob) {
      add_css_class(dom_ob, "loading");
    }

    ajax("object_load_more_tags", param, this.load_more_tags_callback.bind(this, callback, dom_ob));
  }

  // load_more_tags_callback
  this.load_more_tags_callback=function(callback, dom_ob, response) {
    if(!response.return_value) {
      alert("response not valid: "+response.responseText);
      return;
    }

    for(var i in response.return_value)
      this.tags.set(i, response.return_value[i]);

    if(dom_ob) {
      del_css_class(dom_ob, "loading");
      add_css_class(dom_ob, "loaded");
    }

    callback(response.return_value);
  }

  // name
  this.name=function(param) {
    return this.tags.get_lang("name");
  }

  // info
  this.info=function(chapters) {
    this.tags.info(chapters);
  }

  // highlight_geo
  this.highlight_geo=function(param) {
    if(this.highlight) {
      if(this.tags.get("#geo"))
	this.highlight.add_geo([this.tags.get("#geo")]);
    }
  }

  // set_highlight
  this.set_highlight=function(event) {
    var geos=[];

    if(!this.highlight) {
      var geo=this.tags.get("#geo");
      var geo_center=this.tags.get("#geo:center");

      if(!geo) {
	// request from server
	this.load_more_tags(["#geo"], this.highlight_geo.bind(this), event.target);
      }
      else {
	if(dom_ob) {
	  add_css_class(dom_ob, "loaded");
	}
	geos.push(geo);
      }
      if(!geo_center) {
	geo_center=geo;
      }

      this.highlight=new highlight(geos, geo_center);
    }

    this.highlight.show();
  }

  // unset_highlight
  this.unset_highlight=function() {
    if(!this.highlight)
      return;

    this.highlight.hide();
  }

  // info_show_real
  this.info_show_real=function() {
    var way=new postgis(this.tags.get("#geo"));
    this.info_features=way.geo();
    set_feature_style(this.info_features,
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
    vector_layer.addFeatures(this.info_features);

    this.info_features_extent=new OpenLayers.Bounds();
    for(var i=0; i<this.info_features.length; i++) {
      this.info_features_extent.extend(this.info_features[i].geometry.getBounds());
    }

    var zoom=map.getZoomForExtent(this.info_features_extent);
    if(zoom>15)
      zoom=15;

    var center;
    if(this.tags.get("#geo:center")) {
      center=new postgis(this.tags.get("#geo:center")).geo();
      if(center[0]&&center[0].geometry)
	pan_to_highlight(center[0].geometry.x, center[0].geometry.y, zoom);
    }
    else {
      center=this.info_features_extent.getCenterPixel();
      pan_to_highlight(center.x, center.y, zoom);
    }

  }

  // info_show
  this.info_show=function(info_ob) {
    if(!this.tags.get("#geo")) {
      // request from server
      this.load_more_tags(["#geo"], this.info_show_real.bind(this));
    }
    else
      this.info_show_real();
  }

  // info_hide
  this.info_hide=function(info_ob) {
    if(this.info_features)
      vector_layer.removeFeatures(this.info_features);
  }

  // show_element
  this.list_element=function() {
    var li=document.createElement("li");
    li.className="list";

    // href
    var a=dom_create_append(li, "a");
    if(this.id)
      a.href="#"+this.id;

    a.onmouseover=this.set_highlight.bind(this);
    a.onmouseout=this.unset_highlight.bind(this);

    // name
    dom_create_append_text(a, this.name());

    return li;
  }

  // constructor
  this.tags=new tags();
  if(dom.nodeType) {
    this.tags.readDOM(dom);
    this.id=dom.getAttribute("id");
    this.id_split=split_semicolon(dom.getAttribute("id"));
  }
  else {
    this.tags.set_data(dom.osm_tags);
    this.id=dom.osm_id;
    this.id_split=split_semicolon(dom.osm_id);
  }
}

function osm_object_load(id, callback) {
  var ajax_callback=null;
  if(callback)
    ajax_callback=osm_object_load_callback.bind(this, (typeof id=="string"), callback);

  var response=ajax("load_object", { "ob": id }, ajax_callback);

  if(!callback)
    return osm_object_load_callback((typeof id=="string"), null, response);
}

function osm_object_load_callback(single_object, callback, response) {
  var data=response.responseXML;
  var ret=[];

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var matches=data.getElementsByTagName("match");
  for(var i=0; i<matches.length; i++) {
    ret.push(new osm_object(matches[i]));
  }

  if(single_object) {
    if(ret.length)
      ret=ret[0];
    else
      ret=null;
  }

  if(!callback)
    return ret;
  else
    callback(ret);
}

function osm_object_search_object(ret, id, callback) {
  var id_parts=id.split("_");
  if(in_array(id_parts[0], osm_object_valid_prefixes)) {
    var ob=osm_object_load(id, callback);
    ret.push(ob);
  }
}

register_hook("search_object", osm_object_search_object);
