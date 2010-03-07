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
