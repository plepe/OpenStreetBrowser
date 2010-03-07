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

  if(l=str.match(/^tag:[^=]*=(.*)$/))
    return l[1];

  if(l=str.match(/^[^:]*:(.*)$/))
    return l[1];

  return str;
}

function lang_change(key, value) {
  // When the UI language changed, we have to reload
  if(key=="ui_lang") {
    if(value!=ui_lang) {
      var old_href=location.search+location.hash;
      var new_href=permalink();

      var old_path=old_href;
      var old_hash=old_href.indexOf("#");
      if(old_hash!=-1)
	old_path=old_href.substr(0, old_hash);

      var new_path=new_href;
      var new_hash=new_href.indexOf("#");
      if(new_hash!=-1)
	new_path=new_href.substr(0, new_hash);

      // Set new path
      location.href=new_href;
      // Same URL ... reload
      if(old_path==new_path) {
	location.reload();
      }
    }
  }

  // When the data language changed, we just remember
  // TODO: reload data
  if(key=="data_lang") {
    data_lang=value;
  }
}

function lang_init() {
  if(!options_get("ui_lang"))
    options_set("ui_lang", ui_lang);
  if(!options_get("data_lang"))
    options_set("data_lang", data_lang);
}

register_hook("options_change", lang_change);
register_hook("init", lang_init);
