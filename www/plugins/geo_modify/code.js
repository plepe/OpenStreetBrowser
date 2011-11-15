var geo_modify_current;

function geo_modify(info, ob) {
  this.ob=ob;

  this.load();

  // create geo_modify chapter in info-box
  this.div=document.createElement("div");
  info.push({
    head: "geo_modify",
    weight: 5,
    content: this.div
  });
}

geo_modify.prototype.load=function() {
  // call ajax-function
  var param={};
  param.fun="get_center";
  param.id=this.ob.id;
  param.zoom=map.zoom;
  param.param={ };
  ajax("geo_modify", param, this.load_callback.bind(this));
}

geo_modify.prototype.load_callback=function(result) {
  var value=result.return_value;

  geo_modify_info_hide();

  // show modified geometry
  var geo=new postgis(value.way);
  this.features=geo.geo();
  this.features[0].style= { pointRadius: 3, fillColor: "#ff0000", strokeColor: "#ff0000" };
  vector_layer.addFeatures(this.features);

  // show additional #geo_modify-tags
  var ul=dom_create_append(this.div, "ul");
  for(var i in value.tags) {
    var k;
    if(k=i.match(/^#geo_modify:(.*)/)) {
      var li=dom_create_append(ul, "li");
      dom_create_append_text(li, k[1]+": "+value.tags[i]);
    }
  }
}

geo_modify.prototype.hide=function() {
  if(this.features) {
    vector_layer.removeFeatures(this.features);
    delete(this.features);
  }
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
