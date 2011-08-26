var lang_str={};
var lang_tags_format_options_default={
  str_join: ", ", value_separator: ": ", count: 1
};

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
      if(typeof(l[0])=="number")
        i++;

      return l[i];
    }
    else if(l.length==1)
      return l[0];
  }

  debug(str, "language string missing");

  if(l=str.match(/^tag:([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/)) {
    return l[3];
  }
  else if(l=str.match(/^tag:([^><=!]*)$/)) {
    return l[1];
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

/**
 * format tag(s) for display
 * @param string|array string(s) to translate
 *   str_join: string which is used to join strings (default: ", ")
 *   value_separator: string which is used to join key and value (default: ": ")
 *   count: count of strings (for singular/plural) (default: 1)
 * @param hash options to configure display
 */
function lang_tags_format(str, count, options) {
  var ret;

  // default values
  if(typeof options=="undefined")
    options={};
  options=array_merge(lang_tags_format_options_default, options);

  // if array than iterate through str and join as string
  if(typeof str=="object") {
    ret=[];
    for(var i=0; i<str.length; i++)
      ret.push(lang_tags_format(str[i], count, options));

    return ret.join(options.str_join);
  }

  // if it matches as a tag-string than process each of them
  var m;
  if(m=str.match(/^([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/)) {
    if(m[2]=="=")
      m[2]=options.value_separator;

    ret=lang(m[1], count)+m[2]+lang(m[1]+"="+m[3], count);

    return ret;
  }

  // it doesn't match as tag-string -> just translate
  return lang(str);
}

function lang_init() {
  if(!options_get("ui_lang"))
    options_set("ui_lang", ui_lang);
  if(!options_get("data_lang"))
    options_set("data_lang", data_lang);
}

register_hook("options_change", lang_change);
register_hook("init", lang_init);
