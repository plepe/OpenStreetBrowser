var lang_str={};

function change_language() {
  var ob=document.getElementById("lang_select_form");
//  ob.action=get_permalink();
  ob.submit();
}

function lang_element(str, count) {
  var l;

  if(l=lang_str[str]) {
    if(typeof(l)=="string")
      return l;

    var i;
    if(l.length>1) {
      if((count===0)||(count>1))
        i=1;
      else
        i=0;

      // if a Gender is defined, shift values
      if(typeof(l[0]=="number"))
        i++;

      return l[i];
    }
    else if(l.length==1)
      return l[0];
  }

  debug(str, "language string missing");

  if(l=str.match(/^tag:([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/)) {
    if(lang_str["tag:"+l[1]])
      return lang("tag:"+l[1])+l[2]+l[3];
    else
      return l[1]+l[2]+l[3];
  }

  if(l=str.match(/^[^:]*:(.*)$/))
    return l[1];

  return str;
}

function lang(str, count) {
  var el=lang_element(str, count);

  if(arguments.length<=2)
    return el;

  var vars=[];
  for(var i=2;i<arguments.length;i++) {
    vars.push(arguments[i]);
  }

  return vsprintf(el, vars);
}

function t(str, count) {
  // TODO: write deprecation message to debug

  return lang(str, count);
}

function lang_change(key, value) {
  // When the UI language changed, we have to reload
  if(key=="ui_lang") {
    if(value!=ui_lang) {
      // create new path
      var new_href=get_baseurl()+"#?"+hash_to_string(get_permalink());

      // Set new path and reload
      location.href=new_href;
      location.reload();
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
