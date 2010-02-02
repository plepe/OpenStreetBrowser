var lang_str={};

function change_language() {
  var ob=document.getElementById("lang_select_form");
//  ob.action=get_permalink();
  ob.submit();
}

function t(str, count) {
  var l;

  if(l=lang_str[str]) {
    if((l.length>1)&&(count==1))
      return l[0];
    else if(l.length>1)
      return l[1];
    else
      return l[0];
  }

  return str;
}
