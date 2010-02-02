function box_change(ob) {
  ob.form.submit();
}

function box_click(boxname, subboxname) {
  var ob;

  call_hooks("list_request", boxname, subboxname);

  if(!subboxname)
    ob=document.getElementsByName(boxname);
  else
    ob=document.getElementsByName(boxname+"|"+subboxname);

  ob=ob[0];
  ob.checked=!ob.checked;

  box_change(ob);
}

function list_entry(ob) {
  var add="";
  var li_style="";
  var title="";

  if(ob.getAttribute("description"))
    add=" ("+ob.getAttribute("description")+")";

  if(ob.getAttribute("icon")) {
    var icon=ob.getAttribute("icon");
    var icon_data;

    if(icon_data=icon.match(/^\[\[Image:(.*)\]\]$/)) {
      icon_data=icon_data[1].replace(/ /g, "_");
      li_style+="list-style-image: url('symbols/"+icon_data+"'); ";
    }
  }

  var title_parts=ob.getAttribute("data").split("/");
  for(var i=0; i<title_parts.length; i++) {
    title+=" "+title_parts[i];
  }

  return "<li class='listitem' style=\""+li_style+"\" id='list_"+ob.getAttribute("id")+"' title='"+title+"'><element id='"+ob.getAttribute("id")+"'><a href='#"+ob.getAttribute("id")+"' onMouseOver='set_highlight([\""+ob.getAttribute("id")+"\"])' onMouseOut='unset_highlight()'>"+ob.getAttribute("name")+"</a>"+add+"</element></li>\n";
}
