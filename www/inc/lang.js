var lang_str={};
var data_lang="";

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

  if(l=str.match(/^tag:[^=]*=(.*)$/))
    return l[1];

  if(l=str.match(/^[^:]*:(.*)$/))
    return l[1];

  return str;
}

function lang_change(key, value) {
  if(key=="ui_lang") {
    if(value!=ui_lang) {
      location.href=permalink();
      location.reload();
    }
  }
  if(key=="data_lang") {
    data_lang=value;
  }
}

function lang_init() {
  data_lang=options_get("data_lang");
}

register_hook("options_change", lang_change);
register_hook("init", lang_init);
