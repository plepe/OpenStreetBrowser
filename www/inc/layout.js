function box_change(ob) {
  info_change();
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
  var x;
  tag=new tags({});
  tag.readDOM(ob);

  if(x=tag.get("display_type"))
    add=" ("+x+")";

  if(x=tag.get("icon")) {
    var icon=x;
    var icon_data;

    if(icon_data=icon.match(/^\[\[Image:(.*)\]\]$/)) {
      icon_data=icon_data[1].replace(/ /g, "_");
      li_style+="list-style-image: url('symbols/"+icon_data+"'); ";
    }
  }

  var title_parts;
  if(title_parts=ob.getAttribute("data")) {
    title_parts=title_parts.split(/ /);
    title=[];
    for(var i=0; i<title_parts.length; i++) {
      var title_parts_key=title_parts[i].split(/=/);
      title.push(t("tag:"+title_parts_key[0], 1)+"="+t("tag:"+title_parts[i], 1));
    }
    title=title.join(", ");
  }
  else
    title="";

  var name=tag.get("display_name");
  var id=ob.getAttribute("id");
  if(!name)
    name=t("unnamed");

  return "<li class='listitem' style=\""+li_style+"\" id='list_"+id+"' title='"+title+"'><element id='"+id+"' rule_id='"+ob.getAttribute("rule_id")+"'+><a href='#"+id+"' onMouseOver='set_highlight([\""+id+"\"])' onMouseOut='unset_highlight()'>"+name+"</a>"+add+"</element></li>\n";
}
