var list_open={"culture": 1, "culture/religion": 1};

function box_open(head, path, content) {
  var ret="";
  var level=path.split(/\//).length;

  ret+="<div class='box_open_"+level+"' id='list_"+path+"'>\n";
  ret+="<h"+level+"><input type='checkbox' name='"+path+"' "+
       (content==null?"":" checked='checked'")+
       " onChange='box_change(this)' />"+
       "<a href='javascript:box_click(\""+path+"\")'>"+
       head+"</a></h"+level+">\n";
  if(content!=null)
    ret+=content;
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
