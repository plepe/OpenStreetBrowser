function box_change(ob) {
  ob.form.submit();
}

function box_click(boxname, subboxname) {
  var ob;

  if(!subboxname)
    ob=document.getElementsByName(boxname);
  else
    ob=document.getElementsByName(boxname+"|"+subboxname);

  ob=ob[0];
  ob.checked=!ob.checked;

  box_change(ob);
}
