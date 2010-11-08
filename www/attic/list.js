// entry in list_cache: [ viewbox, category, data ]
var list_open={};
var list_reload_necessary=1;
var list_reload_working=0;
var category_leaf={};
var category_more={};

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
    ret+=t("nothing_found")+"\n";
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

  viewbox=request.getAttribute("viewbox");

  var cats=data.getElementsByTagName("category");
  for(var cati=0; cati<cats.length; cati++) {
    var cat_dom=cats[cati];
    var cat_id=cat_dom.getAttribute("id");
    var cat_ob=categories[cat_id];
    if(!category_leaf[cat_id])
      continue;
    if(!cat_ob)
      continue;

    cat_ob.push_list(cat_dom, viewbox);

    show_more(cat_ob, viewbox);
  }

  var osm=data.getElementsByTagName("osm");
  load_objects_from_xml(osm);
}

function show_more(cat, viewbox, count) {
  var cat_id=cat.id;

  var div=document.getElementById("content_"+cat_id);
  if(div) {
    var ul=div.getElementsByTagName("ul");
    ul=ul[0];

    var count=category_more[cat_id];
    if(!count)
      count=10;
    category_more[cat_id]=count;

    var text=cat.write_list(viewbox, 0, count);
    ul.innerHTML=text;
  }

  if((cat.count_list(viewbox)>count)||(!cat.is_complete())) {
    div.lastChild.innerHTML="<a href='javascript:list_more(\""+cat.id+"\", 10)'>"+t("more")+"</a>\n";
  }
  else {
    div.lastChild.innerHTML="";
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

  return;
}

function list_load_more(cat, viewbox, new_count) {
  var there=[];
  var cur_cache;
  if(!(cur_cache=list_cache.search_element(viewbox, cat.id))) {
    return null;
  }

  if(list_reload_working||list_reload_necessary) {
    return;
  }
  list_reload_working=1;

  if(cur_cache)
    for(var i=0; i<cur_cache.data.length; i++) {
      there.push(cur_cache.data[i].id);
    }

  ajax_direct("list.php", { "viewbox": viewbox, "zoom": map.zoom, "exclude": there.join(","), "category": cat.id, "count": new_count }, list_more_call_back);
}

function list_more(cat_id, new_count) {
  var cat=categories[cat_id];
  if(!new_count)
    new_count=10;
  var x=map.calculateBounds();
  var viewbox=x.left +","+ x.top +","+ x.right +","+ x.bottom;

  var cur_cache;
  if(!(cur_cache=list_cache.search_element(viewbox, cat.id))) {
    return;
  }

  var now_count=category_more[cat.id];
  if(!now_count)
    now_count=10;

  var loaded=cat.count_list(viewbox);
  shall_count=now_count+new_count;
  category_more[cat.id]=shall_count;

  if((loaded>=shall_count)||(cur_cache.complete)) {
    show_more(cat, viewbox);
    return;
  }

  var div=document.getElementById("more_"+cat.id);
  if(div)
    div.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+t("loading");

  list_load_more(cat, viewbox, shall_count-loaded);
}

function list_reload(info_lists) {
  if(!map)
    return;

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
	category_more[info_lists[i]]=10;
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
	div.innerHTML="<ul></ul><div id='more_"+info_lists[i]+"'><img class='loading' src='img/ajax_loader.gif'> "+t("loading")+"</div>";
    }

    call_hooks("category_show_reset", categories[info_lists[i]]);
  }

  if(info_lists.length==0) {
    list_reload_working=0;
    return;
  }

  ajax_direct("list.php", { "viewbox": viewbox, "zoom": map.zoom, "category": info_lists.join(",") }, list_more_call_back);
}

function get_sub_lists(cat) {
  var cat_ex=cat.split("/");
  var cat_list=category_list;
  var ret=[];

  for(var i=0; i<cat_ex.length; i++) {
    cat_list=cat_list[cat_ex[i]];
  }

  for(var i in cat_list) {
    var c=cat+"/"+i;
    ret.push(c);
    ret.push(get_sub_lists(c));
  }

  return ret;
}

function new_box_change(ob) {
  var div=document.getElementById("content_"+ob.name);
  var list=document.getElementById("list_"+ob.name);
  var level=ob.name.split("/").length;

  if(ob.checked) {
    list_open[ob.name]=1;
    list.className="box_open_"+level;
    
    div=document.createElement("div");
    div.className="box_content_"+level+"_open";
    div.id="content_"+ob.name;
    list.appendChild(div);
  }
  else {
    delete list_open[ob.name];
    list.className="box_closed_"+level;
    div.parentNode.removeChild(div);
  }

  if(ob.checked) {
    div.innerHTML=show_sub_list(ob.name);
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

    call_hooks("show_category", ob.name);
  }
  else {
    call_hooks("hide_category", ob.name);
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

  var lists=get_sub_lists(path);
  lists.push(path);

  if(state==true) {
    for(var i=0; i<lists.length; i++) {
      if((o=overlays[lists[i]])&&(list_open[lists[i]])) {
	overlays_layers[o].setVisibility(true);
      }
    }
  }
  else {
    for(var i=0; i<lists.length; i++) {
      if(o=overlays[lists[i]]) {
	overlays_layers[o].setVisibility(false);
      }
    }
  }
}

function box_open(head, path, content, state) {
  var cat=categories[path];
  var ret="";
  var level=path.split(/\//).length;
  if(!state)
    state="open";

  var name;
  if(!cat)
    name="undefined";
  else
    name=cat.tags.get_lang("name");

  ret+="<div class='box_"+state+"_"+level+"' id='list_"+path+"'>\n";
  ret+="<h"+level+"><input type='checkbox' id='check_"+path+"' name='"+path+"' "+
       (content==null?"":" checked='checked'")+
       " onChange='new_box_change(this)' />"+
       "<a href='javascript:new_box_click(\""+path+"\")'>"+
       name+"</a></h"+level+">\n";

  if(state=="open") {
    ret+="<div class='box_content_"+level+"_"+state+"' id='content_"+path+"'>\n";
    if(content!=null)
      ret+=content;
    ret+="</div>\n";

    call_hooks("show_category", path);
  }
  else {
    call_hooks("hide_category", path);
  }

  ret+="</div>\n";

  return ret;
}

function box_closed(head, path) {
  return box_open(head, path, null, "closed");
}

function show_list() {
  var ret="";
  var level;
  var _list;

  _list=category_list;
  level=0;
  ret+="<div class='list_info'>"+t("list_info")+"</div>\n";

  ret+=show_sub_list("", _list);

  var ob=document.getElementById("details_content");
  ob.innerHTML=ret;

  call_hooks("show_list", ob);
  list_reload();

  return ret;
}

function show_sub_list(path, _list) {
  var ret="";
  var p;
  var level;

  level=path.split(/\//).length;
  if(!_list) {
    p=path.split(/\//);
    _list=category_list;

    for(var i=0; i<p.length; i++)
      _list=_list[p[i]];
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
      var r=show_sub_list(p, _list[i]);
      ret+=box_open(i, p, r);
    }
    else {
      ret+=box_closed(i, p);
    }
  }

  return ret;
}
