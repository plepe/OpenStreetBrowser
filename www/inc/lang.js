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
      var old_href=location.search+location.hash;
      var new_href=permalink();
      location.href=new_href;

      var old_path=old_href.substr(0, old_href.indexOf("#")||old_href.length);
      var new_path=new_href.substr(0, new_href.indexOf("#")||new_href.length);
      // Same URL ... reload
      if(old_path==new_path) {
	location.reload();
      }
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
