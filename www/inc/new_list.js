var list_open={"culture": 1, "culture/religion": 1};

function load_list(path) {

}

function new_box_change(ob) {
  var div=document.getElementById("content_"+ob.name);
  div.innerHTML="";

  if(list_open[ob.name]) {
    delete list_open[ob.name];
  }
  else {
    list_open[ob.name]=1;
    div.innerHTML=show_list(ob.name);
    list_reload([ob.name]);
  }
}

function box_click(boxname, subboxname) {
  var ob;

  if(!subboxname)
    ob=document.getElementsByName(boxname);
  else
    ob=document.getElementsByName(boxname+"|"+subboxname);

  ob=ob[0];
  ob.checked=!ob.checked;

  new_box_change(ob);
}

function box_open(head, path, content) {
  var ret="";
  var level=path.split(/\//).length;

  ret+="<div class='box_open_"+level+"' id='list_"+path+"'>\n";
  ret+="<h"+level+"><input type='checkbox' name='"+path+"' "+
       (content==null?"":" checked='checked'")+
       " onChange='new_box_change(this)' />"+
       "<a href='javascript:new_box_click(\""+path+"\")'>"+
       head+"</a></h"+level+">\n";
  ret+="<div class='box_content' id='content_"+path+"'>\n";
  if(content!=null)
    ret+=content;
  ret+="</div>\n";
  ret+="</div>\n";

  return ret;
}

function box_closed(head, path) {
  return box_open(head, path, null);
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
    level=path.split(/\//);
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

    if(list_open[p]) {
      var r=show_list(p, _list[i]);
      ret+=box_open(i, p, r);
    }
    else
      ret+=box_closed(i, p);
  }

  if(path=="") {
    var ob=document.getElementById("details_content");
    ob.innerHTML=ret;
  }

  return ret;
}
