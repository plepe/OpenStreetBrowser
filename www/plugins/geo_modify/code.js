var geo_modify_features;
var geo_modify_div;

function geo_modify_info_hide() {
  if(geo_modify_features) {
    vector_layer.removeFeatures(geo_modify_features);
    delete(geo_modify_features);
  }
}

function geo_modify_info_show(info, ob) {
  // call ajax-function
  var param={};
  param.fun="grid";
  param.id=ob.id;
  param.zoom=map.zoom;
  param.param={ radius: -100 };

  ajax("geo_modify", param, geo_modify_info_show_callback);

  // create geo_modify chapter in info-box
  geo_modify_div=document.createElement("div");
  info.push({
    head: "geo_modify",
    weight: 5,
    content: geo_modify_div
  });
}

function geo_modify_info_show_callback(result) {
  var value=result.return_value;

  geo_modify_info_hide();

  // show modified geometry
  var geo=new postgis(value.way);
  geo_modify_features=geo.geo();
  vector_layer.addFeatures(geo_modify_features);

  // show additional #geo_modify-tags
  var ul=dom_create_append(geo_modify_div, "ul");
  for(var i in value.tags) {
    var k;
    if(k=i.match(/^#geo_modify:(.*)/)) {
      var li=dom_create_append(ul, "li");
      dom_create_append_text(li, k[1]+": "+value.tags[i]);
    }
  }
}

register_hook("info", geo_modify_info_show);
register_hook("info_hide", geo_modify_info_hide);
