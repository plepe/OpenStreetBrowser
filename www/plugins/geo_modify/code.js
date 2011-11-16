var geo_modify_current;
var geo_modify_config;
var geo_modify_color_list=[ '#009f00', '#9f0000', '#008f8f', '#00009f', '#8f008f', '#8f8f00', '#009f3f', '#003f9f', '#3f009f', '#9f003f', '#3f9f00', '#9f3f00' ];

function geo_modify(info, ob) {
  this.ob=ob;

  // create geo_modify chapter in info-box
  var chapter=document.createElement("div");
  info.push({
    head: lang("geo_modify:name"),
    weight: 5,
    content: chapter
  });

  this.inputs={};

  var d=dom_create_append(chapter, "div");
  this.inputs.debug=dom_create_append(d, "input");
  this.inputs.debug.type="checkbox";
  this.inputs.debug.checked=true;
  if(geo_modify_config)
    this.inputs.debug.checked=geo_modify_config.debug;
  this.inputs.debug.onchange=this.refresh.bind(this);
  dom_create_append_text(d, lang("geo_modify:debug"));

  this.div=dom_create_append(chapter, "div");

  this.load();
}

geo_modify.prototype.load=function() {
  // call ajax-function
  var param={};
  param.fun="area_label";
  param.id=this.ob.id;
  param.zoom=map.zoom;
  param.param={ debug: this.inputs.debug.checked?"true":"false" };
  ajax("geo_modify", param, this.load_callback.bind(this));
}

geo_modify.prototype.load_callback=function(result) {
  var debug_colors={};
  var value=result.return_value;

  geo_modify_info_hide();

  this.debug_features=[];
  var debug_colors={ 'result': '#ff0000' };
  debug_color_index=0;

  // show additional #geo_modify-tags
  var ul=dom_create_append(this.div, "ul");
  var ks=keys(value.tags);
  ks.sort();
  for(var ki=0; ki<ks.length; ki++) {
    var i=ks[ki];
    var k;
    if((k=i.match(/^#geo_modify:([^:]*):/))&&(!debug_colors[k[1]])) {
      debug_colors[k[1]]=geo_modify_color_list[debug_color_index++];
    }

    if(k=i.match(/^#geo_modify:([^:]*):geo/)) {
      var geo=new postgis(value.tags[i]);
      var features=geo.geo();
      for(var j=0; j<features.length; j++) {
	features[j].style= { pointRadius: 3, fillColor: debug_colors[k[1]], strokeColor: debug_colors[k[1]], stroke: true, strokeWidth: 1, strokeOpacity: 1, fillOpacity: 0.2 };
      }
      vector_layer.addFeatures(features);
      this.debug_features.push(features);
    }
    else if(k=i.match(/^#geo_modify:(([^:]*):.*)/)) {
      var li=dom_create_append(ul, "li");
      li.style.color=debug_colors[k[2]];
      dom_create_append_text(li, k[1]+": "+value.tags[i]);
    }
  }

  // show modified geometry
  var geo=new postgis(value.way);
  this.features=geo.geo();
  this.features[0].style= { pointRadius: 3, fillColor: "#ff0000", strokeColor: "#ff0000", stroke: true, strokeWidth: 1 };
  vector_layer.addFeatures(this.features);

}

geo_modify.prototype.refresh=function() {
  this.hide();
  this.load();
}

geo_modify.prototype.hide=function() {
  if(this.features) {
    vector_layer.removeFeatures(this.features);
    delete(this.features);
  }
  if(this.debug_features) {
    for(var i=0; i<this.debug_features.length; i++) {
      vector_layer.removeFeatures(this.debug_features[i]);
    }
    delete(this.debug_features);
  }
  dom_clean(this.div);

  geo_modify_config={ debug: this.inputs.debug.checked };
}

function geo_modify_info_hide() {
  if(geo_modify_current) {
    geo_modify_current.hide();
    delete(geo_modify_current);
  }
}

function geo_modify_info_show(info, ob) {
  geo_modify_current=new geo_modify(info, ob);
}

register_hook("info", geo_modify_info_show);
register_hook("info_hide", geo_modify_info_hide);
