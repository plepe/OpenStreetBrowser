var list_cache=[];
// entry in list_cache: [ viewbox, category, data ]
var list_open={};
var list_reload_necessary=1;
var list_reload_working=0;
var category_leaf={};

list_cache.clean_up=function() {
  while(this.length>10) {
    this.shift();
  }
}

list_cache.search_element=function(viewbox, category) {
  for(var i=0; i<this.length; i++) {
    if((this[i].viewbox==viewbox)&&
       (this[i].category==category))
      return this[i];
  }

  return null;
}

list_cache.search=function(viewbox, category) {
  var ret;

  if(ret=list_cache.search_element(viewbox, category))
    return ret.text;

  return null;
}

function list_make_list(cat) {
  var ret="";
  var places=cat.getElementsByTagName("place");

  if(places.length==0) {
    ret+=t("nothing found")+"\n";
  }

  for(var placei=0; placei<places.length; placei++) {
    var place=places[placei];
    ret+=list_entry(place);
  }

  if(cat.getAttribute("complete")!="true") {
    var cat_id=cat.getAttribute("id")
    ret+="<a id='more_"+cat_id+"' href='javascript:list_more(\""+cat_id+"\")'>"+t("more")+"</a>\n";
  }

  return ret;
}

function list_more_call_back(response) {
  var data=response.responseXML;
  list_reload_working=0;

  if(!data) {
    alert("no data\n"+response.responseText);
    return;
  }

  var request;
  if(request=data.getElementsByTagName("request"))
    request=request[0];

  if(!request) {
    alert("response empty - parsing error?");
    return;
  }

  var cats=data.getElementsByTagName("category");
  for(var cati=0; cati<cats.length; cati++) {
    var cat=cats[cati];
    var cat_id=cat.getAttribute("id");
    if(!category_leaf[cat_id])
      continue;
    var div=document.getElementById("content_"+cat_id);
    var more=document.getElementById("more_"+cat_id);
    if(more)
      more.parentNode.removeChild(more);
    var ul=div.getElementsByTagName("ul");
    ul=ul[0];

    var text=list_make_list(cat);
    ul.innerHTML+=text;

    var ob;
    if(ob=list_cache.search_element(request.getAttribute("viewbox"), cat_id)) {
      ob.text=div.innerHTML;
    }
    else {
      list_cache.push({
	viewbox: request.getAttribute("viewbox"),
	category: request.getAttribute("category"),
	text: div.innerHTML
      });
      list_cache.clean_up();
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

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);

  return;
}

function list_more(cat) {
  var x=map.calculateBounds();

  var div=document.getElementById("more_"+cat);
  if(div)
    div.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+t("loading");

  var there=[];
  div=document.getElementById("content_"+cat);
  var obs=div.getElementsByTagName("element");
  for(var i=0; i<obs.length; i++) {
    var ob=obs[i];
    there.push(ob.getAttribute("id"));
  }

  ajax_direct("list.php", { "viewbox": x.left +","+ x.top +","+ x.right +","+ x.bottom, "zoom": map.zoom, "exclude": there.join(","), "category": cat, "lang": lang }, list_more_call_back);
}

function list_reload(info_lists) {
  var x=map.calculateBounds();
  var form=document.getElementById("details_content");

  if(!x)
    return;
  var viewbox=x.left +","+ x.top +","+ x.right +","+ x.bottom;

  if(!info_lists) {
    var info_lists=[];
    if(form) {
      for(var i=0;i<form.elements.length;i++) {
	if(category_leaf[form.elements[i].name]&&form.elements[i].checked)
	  info_lists.push(form.elements[i].name);
      }
    }
    else
      info_lists=list_open;
  }

  if(list_reload_working) {
    list_reload_necessary=1;
    return;
  }
  list_reload_working=1;
  list_reload_necessary=0;

  for(var i in info_lists) {
    var ret;

    if(ret=list_cache.search(viewbox, info_lists[i])) {
      var div=document.getElementById("content_"+info_lists[i]);
      div.innerHTML=ret;

      delete(info_lists[i]);
    }
  }

  for(var i in info_lists) {
    if(category_leaf[info_lists[i]]) {
      var div=document.getElementById("content_"+info_lists[i]);
      if(div)
	div.innerHTML="<ul><span id='more_"+info_lists[i]+"'><img class='loading' src='img/ajax_loader.gif'> "+t("loading")+"</span></ul>";
    }
  }

  if(info_lists.length==0) {
    list_reload_working=0;
    return;
  }


  ajax_direct("list.php", { "viewbox": viewbox, "zoom": map.zoom, "category": info_lists.join(","), "lang": lang }, list_more_call_back);
}

function new_box_change(ob) {
  var div=document.getElementById("content_"+ob.name);
  var list=document.getElementById("list_"+ob.name);
  div.innerHTML="";

  if(ob.checked) {
    div.innerHTML=show_list(ob.name);
    if(category_leaf[ob.name])
      list_reload([ob.name]);
    else {
      var sub_lists=[];
      var sub_ob=div.getElementsByTagName("input");
      for(var si=0; si<sub_ob.length; si++) {
	if(sub_ob[si].checked)
	  sub_lists.push(sub_ob[si].name);
      }
      list_reload(sub_lists);
    }
  }

  if(ob.checked) {
    list_open[ob.name]=1;
    list.className="box_open_"+ob.name.split("/").length;
  }
  else {
    delete list_open[ob.name];
    list.className="box_closed_"+ob.name.split("/").length;
  }

  list_check_overlays(ob.name, ob.checked);
}

function new_box_click(boxname) {
  var ob=document.getElementById("check_"+boxname);

  if(!ob)
    return;

  ob.checked=!ob.checked;

  new_box_change(ob);
}

function list_check_overlays(path, state) {
  var o;

  if(!overlays_layers)
    return;

  if(o=overlays[path]) {
    overlays_layers[o].setVisibility(state);
  }
}

function box_open(head, path, content, state) {
  var ret="";
  var level=path.split(/\//).length;
  if(!state)
    state="open";

  ret+="<div class='box_"+state+"_"+level+"' id='list_"+path+"'>\n";
  ret+="<h"+level+"><input type='checkbox' id='check_"+path+"' name='"+path+"' "+
       (content==null?"":" checked='checked'")+
       " onChange='new_box_change(this)' />"+
       "<a href='javascript:new_box_click(\""+path+"\")'>"+
       t("cat:"+path)+"</a></h"+level+">\n";
  ret+="<div class='box_content_"+level+"' id='content_"+path+"'>\n";
  if(content!=null)
    ret+=content;
  ret+="</div>\n";
  ret+="</div>\n";

  return ret;
}

function box_closed(head, path) {
  return box_open(head, path, null, "closed");
}

function show_list(path, _list) {
  var ret="";
  var p;
  var level;
  if(!path) {
    path="";
    _list=category_list;
    level=0;
    ret+="<div class='list_info'>"+t("list_info")+"</div>\n";
  }
  else {
    level=path.split(/\//).length;
    if(!_list) {
      p=path.split(/\//);
      _list=category_list;

      for(var i=0; i<p.length; i++)
	_list=_list[p[i]];
    }
  }

  for(var i in _list) {
    if(path!="")
      p=path+"/"+i;
    else
      p=i;

    var check=document.getElementById("check_"+p);

    if(keys(_list[i]).length==0) {
      category_leaf[p]=1;
    }

    if((check&&check.checked)||
       ((!check)&&list_open[p])) {
      var r=show_list(p, _list[i]);
      ret+=box_open(i, p, r);
    }
    else {
      ret+=box_closed(i, p);
    }
  }

  if(path=="") {
    var ob=document.getElementById("details_content");
    ob.innerHTML=ret;
  }

  return ret;
}
