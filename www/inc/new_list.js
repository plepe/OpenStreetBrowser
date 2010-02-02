var list_open={"culture": 1, "culture/culture": 1};

function show_list(path, _list) {
  var ret="";
  var p;
  if(!path) {
    path="";
    _list=category_list;
  }

  for(var i in _list) {
    ret+=i+"<br>\n";
    if(path!="")
      p=path+"/"+i;
    else
      p=i;

    if(list_open[p]) {
      ret+=show_list(p, _list[i]);
    }
  }

  if(path=="") {
    var ob=document.getElementById("details_content");
    ob.innerHTML=ret;
  }

  return ret;
}
