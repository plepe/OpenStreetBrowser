var search_last;

function search_focus(ob) {
  ob.value="";
}

function search(ob) {
  var details_content=document.getElementById("details_content");
  var details=document.getElementById("details");
  location.hash="#search_"+ob.value;
}

function real_search(value, param) {
  if(!param)
    param={};

  param.value=value;

  var x=map.calculateBounds().transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
  var viewbox=[ x.left, x.top, x.right, x.bottom ];
  param.viewbox=viewbox.join(",");

  ajax("search", param, search_result);

  search_last=value;
  details_content.innerHTML="Loading ...";
  details.className="info_loading";
}

function search_result(data) {
  var details_content=document.getElementById("details_content");
  var details=document.getElementById("details");

  var text=data.responseXML.getElementsByTagName("result");
  details_content.innerHTML=text[0].textContent;
  details.className="info";

  var osm=data.responseXML.getElementsByTagName("osm");
  load_objects_from_xml(osm);
}

function search_more() {
  var details_content=document.getElementById("details_content");
  var as=details_content.getElementsByTagName("a");
  var ai;
  var id;
  var shown=[];

  for(ai=0; ai<as.length; ai++) {
    if(id=as[ai].getAttribute("nominatim_id")) {
      shown.push(id);
    }
  }

  return real_search(search_last, { shown: shown.join(",") });
}
