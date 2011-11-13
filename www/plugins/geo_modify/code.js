var geo_modify_features;

function geo_modify_info_hide() {
  if(geo_modify_features) {
    vector_layer.removeFeatures(geo_modify_features);
    delete(geo_modify_features);
  }
}

function geo_modify_info_show(info, ob) {
  var param={};
  param.fun="buffer";
  param.id=ob.id;
  param.param={ radius: -100 };

  ajax("geo_modify", param, geo_modify_info_show_callback);
}

function geo_modify_info_show_callback(result) {
  var value=result.return_value;

  geo_modify_info_hide();

  var geo=new postgis(value.way);
  geo_modify_features=geo.geo();

  vector_layer.addFeatures(geo_modify_features);
}

register_hook("info_show", geo_modify_info_show);
register_hook("info_hide", geo_modify_info_hide);
