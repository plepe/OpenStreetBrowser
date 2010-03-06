var options_win;
var options_list=[ "autozoom" ];
var options_values={};

function options_set(key, value) {
  var expiry=new Date();
  expiry.setTime(expiry.getTime()+365*86400000);

  document.cookie="option:"+key+"="+value+"; expires="+expiry.toGMTString()+"; path=/";
  options_values[key]=value;

  call_hooks("options_change", key, value);
}

function options_get(key) {
  return options_values[key];
}

function options_load() {
  var cookie;
  if(cookie=document.cookie) {
    var cookies=cookie.split(/;/);

    for(var i=0; i<cookies.length; i++) {
      var m;

      if(m=cookies[i].match(/^ *option:([a-zA-Z0-9_]+)=(.*)$/)) {
	options_values[m[1]]=m[2];
      }
    }
  }
}

function save_options() {
  var form=document.getElementById("options_form");

  options_set("autozoom", options_radio_get("autozoom"));
  options_set("ui_lang", options_select_get("ui_lang"));
  options_set("data_lang", options_select_get("data_lang"));

  options_win.close();
  delete(options_win);
}

function close_options() {
  options_win.close();
  delete(options_win);
}

function options_radio(key, values) {
  var ret="";

  ret+="<p>\n";

  var current_value=options_get(key);
  if(!current_value)
    current_value=values[0];

  for(var i=0; i<values.length; i++) {
    ret+="  <input type='radio' name='"+key+"' value='"+values[i]+"'";
    if(current_value==values[i])
      ret+=" checked='checked'";
    ret+="/>"+t("options:"+key+":"+values[i])+"<br/>\n";
  }

  ret+="</p>\n";
  
  return ret;
}

function options_select(key, values) {
  var ret="";

  var values_keys=[];
  for(var i in values)
    values_keys.push(i);

  var current_value=options_get(key);
  if(!current_value)
    current_value=values[0];

  ret+=t("options:"+key)+": ";
  ret+="  <select name='"+key+"'>\n";
  for(var i=0; i<values_keys.length; i++) {
    ret+="  <option value='"+values_keys[i]+"'";
    if(current_value==values_keys[i])
      ret+=" selected='selected'";
    ret+=">"+t(values[values_keys[i]])+"</option>\n";
  }

  ret+="</select>\n";
  
  return ret;
}

function options_radio_get(key) {
  var form=document.getElementById("options_form");

  for(var i=0; i<form.elements[key].length; i++) {
    if(form.elements[key][i].checked)
      return form.elements[key][i].value;
  }

  return null;
}

function options_select_get(key) {
  var form=document.getElementById("options_form");

  return form.elements[key].value;
}

function show_options() {
  var ret;

  options_win=new win("options_win");

  ret ="<form action='javascript:save_options()' id='options_form'>\n";

  ret+="<h4>"+t("options:autozoom")+"</h4>\n";
  ret+="<div class='options_help'>"+t("help:autozoom")+"</div>\n";
  ret+=options_radio("autozoom", [ "pan", "move", "stay" ]);

  ret+="<h4>"+t("options:language_support")+"</h4>\n";
  ret+="<div class='options_help'>"+t("help:language_support")+"</div>\n";
  ret+="<p>\n";
  var ui_langs_x={};
  for(var i=0; i<ui_langs.length; i++)
    ui_langs_x[ui_langs[i]]=language_list[ui_langs[i]];
  ret+=options_select("ui_lang", ui_langs_x);
  ret+="<br/>\n";

  var ui_langs_x={};
  l=language_list;
  l[""]=t("lang:");
  ret+=options_select("data_lang", l);
  ret+="</p>\n";

  ret+="<p><input type='submit' value='"+t("save")+"'>\n";
  ret+="<input type='button' onClick='javascript:close_options()' value='"+t("cancel")+"></p>\n";
  ret+="</form>\n";

  options_win.content.innerHTML=ret;
}

options_load();
