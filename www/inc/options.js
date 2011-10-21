var options_win;
var options_list=[ "autozoom" ];
var options_values={};

function options_set(key, value) {
  var expiry=new Date();
  expiry.setTime(expiry.getTime()+365*86400000);

  document.cookie=key+"="+value+"; expires="+expiry.toGMTString()+"; path=/";
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

      if(m=cookies[i].match(/^ *([a-zA-Z0-9_]+)=(.*)$/)) {
	options_values[m[1]]=m[2];
      }
    }
  }
}

function save_options() {
  var form=document.getElementById("options_form");

  call_hooks("options_save", null);

  options_set("autozoom", options_radio_get("autozoom"));
  options_set("ui_lang", options_select_get("ui_lang"));
  options_set("data_lang", options_select_get("data_lang"));
  if(data_lang=="auto")
    data_lang=ui_lang;

  close_options();
}

function close_options() {
  options_win.close();
  options_win=null;
  delete options_win;
}

function options_radio(key, values) {
  var ret="";

  ret+="<p>\n";

  var current_value=options_get(key);
  if(!current_value)
    current_value=values[0];

  for(var i=0; i<values.length; i++) {
    ret+="  <input type='radio' name='"+key+"' id='"+values[i]+"' value='"+values[i]+"'";
    if(current_value==values[i])
      ret+=" checked='checked'";
    ret+="/><label for='"+values[i]+"'>"+t("options:"+key+":"+values[i])+"</label><br/>\n";
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
  ret+="  <select name='"+key+"' class='icon-menu'>\n";
  for(var i=0; i<values_keys.length; i++) {
    ret+="  <option style=\"background-image: url('img/lang/"+values_keys[i]+".png');\" value='"+values_keys[i]+"'";
    if(current_value==values_keys[i])
      ret+=" selected='selected'";
    ret+=">"+values[values_keys[i]]+"</option>\n";
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
  if(options_win)
    return;

  options_win=new win({ class: "options_win", title: lang("main:options") });


  // Every function which registers to this hooks should
  // push a "weight"-entry to the list, e.g. [ 0, "some text" ]
  var options_list=[];
  call_hooks("options_show", options_list);

  var ret=document.createElement("form");
  ret.action="javascript:save_options()";
  ret.id="options_form";

  var parts=dom_create_append(ret, "div");
  parts.className="options_parts";

  options_list=weight_sort(options_list);
  for(var i=0; i<options_list.length; i++) {
    if(typeof options_list[i]=="string") {
      var d=dom_create_append(parts, "div");
      d.innerHTML=options_list[i];
    }
    else {
      parts.appendChild(options_list[i]);
    }
  }

  var ret1;
  ret1 ="<h4>"+t("options:autozoom")+"</h4>\n";
  ret1+="<div class='options_help'>"+t("help:autozoom")+"</div>\n";
  ret1+=options_radio("autozoom", [ "pan", "move", "stay" ]);
  var d=dom_create_append(parts, "div");
  d.innerHTML=ret1;

  ret1 ="<h4>"+t("options:language_support")+"</h4>\n";
  ret1+="<div class='options_help'>"+t("help:language_support")+"</div>\n";
  ret1+="<p>\n";

  var ui_langs_x={};
  for(var i=0; i<ui_langs.length; i++) {
    var str=language_list[ui_langs[i]];
    if(lang("lang:"+ui_langs[i])!=str)
      str+=" ("+lang("lang:"+ui_langs[i])+")";
    ui_langs_x[ui_langs[i]]=str;
  }

  // if ui_lang is set to a not defined UI lang add this option
  if(!in_array(ui_langs, ui_lang)) {
    var str="";
    if(language_list[ui_lang])
      str=language_list[ui_lang];
    else
      str=ui_lang;

    if(lang("lang:"+ui_lang)!=str)
      str+=" ("+lang("lang:"+ui_lang)+")";

    ui_langs_x[ui_lang]=str;
  }

  ret1+=options_select("ui_lang", ui_langs_x);
  ret1+="<br/>\n";

  var ui_langs_x={};
  l=[];
  l[""]="";
  l["auto"]=t("lang:auto");
  for(var i in language_list) {
    l[i]=language_list[i];
    if(lang("lang:"+i)!=l[i])
      l[i]+=" ("+lang("lang:"+i)+")";
  }
  l[""]=t("lang:");

  ret1+=options_select("data_lang", l);
  ret1+="</p>\n";

  var add=[];
  call_hooks("options_lang", add);
  ret1+=add.join(" |\n");
  var d=dom_create_append(parts, "div");
  d.innerHTML=ret1;

  var d=dom_create_append(ret, "div");
  d.className="options_interact";

  var i=dom_create_append(d, "input");
  i.type="submit";
  i.value=lang("save");

  var i=dom_create_append(d, "input");
  i.type="button";
  i.onclick=close_options;
  i.value=lang("cancel");

  options_win.content.appendChild(ret);
}

options_load();
