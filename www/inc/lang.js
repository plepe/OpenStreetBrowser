var lang_str={};

function change_language() {
  var ob=document.getElementById("lang_select_form");
//  ob.action=get_permalink();
  ob.submit();
}

function t(str, count) {
  var l;

  if(l=lang_str[str]) {
    if((l.length>1)&&(count==1))
      return l[0];
    else if(l.length>1)
      return l[1];
    else
      return l[0];
  }

  if(l=str.match(/^tag:[^=]*=(.*)$/))
    return l[1];

  if(l=str.match(/^[^:]*:(.*)$/))
    return l[1];

  return str;
}

function lang_change(key, value) {
  if(key=="ui_lang") {
    if(value!=lang) {
      var center=map.getCenter().transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
      location.href="http://www.openstreetbrowser.org/skunk/?zoom="+map.zoom+"&lat="+center.lat+"&lon="+center.lon;
    }
  }
}

register_hook("options_change", lang_change);
