var list_open={"culture": 1, "culture/religion": 1};
var category_leaf={};
var overlays={
  "gastro": "food",
  "services": "services",
  "culture": "culture",
  "shop": "shop",
  "transport/pt": "pt",
  "transport/ch": "ch",
  "transport/car": "car"
};

function load_list(path) {

}

function new_box_change(ob) {
  var div=document.getElementById("content_"+ob.name);
  div.innerHTML="";

  if(ob.checked) {
    div.innerHTML=show_list(ob.name);
    list_reload([ob.name]);
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
       head+"</a></h"+level+">\n";
  if(content!=null)
    ret+=content;
  ret+="<div class='box_content_"+level+"' id='content_"+path+"'>\n";
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

    if(check&&check.checked) {
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
