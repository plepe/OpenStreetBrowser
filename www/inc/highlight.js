var highlight_feature=[];
var highlight_feature_timer;

function pan_to_highlight(lon, lat) {
  map.panTo(new OpenLayers.LonLat(lon, lat));
}

var last_highlight_request;

function set_highlight_after_loading(response) {
  var data=response.responseXML;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);

  var req=data.getElementsByTagName("request");
  req=req[0];
  var r=req.firstChild;
  while(r) {
    loaded_list[r.getAttribute("id")]=1;
    r=r.nextSibling;
  }

  real_set_highlight();
}

function real_set_highlight() {
  var new_highlight_feature=[];

  for(var i=0; i<last_highlight_request.length; i++) {
    var el=get_loaded_object(last_highlight_request[i]);
    if(el) {
      var hl=el.get_highlight();
      new_highlight_feature=new_highlight_feature.concat(hl);

      var ob=document.getElementById("list_"+last_highlight_request[i]);
      if(ob)
	ob.className="listitem_highlight";
    }
    else {
      if(loaded_list[last_highlight_request[i]]) {
	var ob=document.getElementById("list_"+last_highlight_request[i]);
	if(ob)
	  ob.className="listitem_notfound";
      }
    }
  }

  vector_layer.addFeatures(new_highlight_feature);
  for(var i in new_highlight_feature)
    highlight_feature.push(new_highlight_feature[i]);
}

function set_highlight(list, dont_load) {
  //highlight_feature=[];
  if(highlight_feature_timer)
    clearTimeout(highlight_feature_timer);

  var load_list=[];
  last_highlight_request=list;

  for(var i=0; i<list.length; i++) {
    var el=get_loaded_object(list[i]);
    if(!el) {
      load_list.push(list[i]);

      var ob=document.getElementById("list_"+list[i]);
      if(ob)
	ob.className="listitem_loading";
    }
  }

  if(load_list.length&&(!dont_load)) {
    ajax("load_object", { "obj": load_list, "lang": lang}, set_highlight_after_loading);
  }

  real_set_highlight();


//  lon=lon/list.length;
//  lat=lat/list.length;
//  highlight_feature_timer=setTimeout("pan_to_highlight("+lon+", "+lat+")", 500);
}

function unset_highlight() {
  if(highlight_feature_timer)
    clearTimeout(highlight_feature_timer);

  var obs=document.getElementsByTagName("li");
  for(var i=0; i<obs.length; i++) {
    if(obs[i].id&&obs[i].id.match(/^list_/)) {
      obs[i].className="listitem";
    }
  }

  vector_layer.removeFeatures(highlight_feature);
  highlight_feature=[];
  last_highlight_request=[];
}
