var list_last=[];
var list_reload_necessary=1;
var list_reload_working=0;

function list_call_back(response) {
  var data=response.responseXML;
  list_reload_working=0;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var info_content=document.getElementById("details_content");
  var map_div=document.getElementById("map");
  var info=document.getElementById("details");

  info.className="info";
  var cats=data.getElementsByTagName("category");
  for(var cati=0; cati<cats.length; cati++) {
    var cat=cats[cati];
    var cat_id=cat.getAttribute("id");
    if(!category_leaf[cat_id])
      continue;
    var div=document.getElementById("content_"+cat_id);
    var ret="<ul>";
    if(div) {
      var places=cat.getElementsByTagName("place");
      for(var placei=0; placei<places.length; placei++) {
	var place=places[placei];
	ret+="<li>"+place.getAttribute("name")+"</li>\n";
      }

      ret+="</ul>\n";
      div.innerHTML=ret;
    }
  }
//  map_div.className="map";
//  var text_node=data.getElementsByTagName("text");
//  if(text_node) {
//    if(!text_node[0])
//      show_msg("Returned data invalid", response.responseText);
//    var text=get_content(text_node[0]);
////alert(text_node[0].nodeValue);
////    info_content.appendChild(text_node[0].cloneNode(true));
//    info_content.innerHTML=text;
//  }

  if(list_reload_necessary) {
    list_reload();
  }

  check_overlays(data);

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);

  return;
}

function list_reload(info_lists) {
  var x=map.calculateBounds();
  var form=document.getElementById("details_content");

  if(!x)
    return;

  if(!info_lists) {
    var info_lists=[];
    if(form) {
      for(var i=0;i<form.elements.length;i++) {
	if(form.elements[i].checked)
	  info_lists.push(form.elements[i].name);
      }
    }
    else
      info_lists=list_last;
  }

  if(list_reload_working) {
    list_reload_necessary=1;
    return;
  }
  list_reload_working=1;
  list_reload_necessary=0;

  for(var i in info_lists) {
    if(category_leaf[info_lists[i]]) {
      var div=document.getElementById("content_"+info_lists[i]);
      if(div)
	div.innerHTML="<img src='img/ajax_loader.gif'> loading";
    }
  }

  ajax_direct("list.php", { "viewbox": x.left +","+ x.top +","+ x.right +","+ x.bottom, "zoom": map.zoom, "category": info_lists.join(","), "lang": lang }, list_call_back);

  var info_content=document.getElementById("details_content");
  var map_div=document.getElementById("map");
  var info=document.getElementById("details");

  info.className="info_loading";
//  map_div.className="map";
//  if(showing!="list_routes") {
//    info_content.innerHTML="<div class=\"loading\"><img src=\"img/ajax_loader.gif\" /> loading</div>";
//  }

  list_last=info_lists;
}
