var osm_object_valid_prefixes=[ "node", "way", "rel" ];

function osm_object(dom) {
  this.inheritFrom=geo_object;
  this.inheritFrom();

  // load_more_data
  this.load_more_tags=function(tags, callback) {
    var param={};
    param.id=this.id;
    param.tags=tags.join(",");

    ajax("object_load_more_tags", param, this.load_more_tags_callback.bind(this, callback));
  }

  // load_more_tags_callback
  this.load_more_tags_callback=function(callback, response) {
    if(!response.return_value) {
      alert("response not valid: "+response.responseText);
      return;
    }

    for(var i in response.return_value)
      this.tags.set(i, response.return_value[i]);

    callback(response.return_value);
  }

  // highlight_geo
  this.highlight_geo=function(param) {
    if(this.highlight) {
      if(this.tags.get("geo"))
	this.highlight.add_geo([this.tags.get("geo")]);
    }
  }

  // set_highlight
  this.set_highlight=function() {
    var geos=[];

    if(!this.highlight) {
      var geo=this.tags.get("geo");
      var geo_center=this.tags.get("#geo:center");

      if(!geo) {
	// request from server
	this.load_more_tags(["geo"], this.highlight_geo.bind(this));
      }
      else {
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

  // constructor
  this.tags=new tags();
  this.tags.readDOM(dom);
  this.id=dom.getAttribute("id");
  this.id_split=split_semicolon(dom.getAttribute("id"));
}
