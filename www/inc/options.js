var options_win;
var options_list=[ ];
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

  close_options();
}

function close_options() {
  options_win.close();
  options_win=null;
  delete options_win;
}

function options_checkbox(key, values) {
  var ret="";

  ret+="<p>\n";

  var current_value=options_get(key);
  if(typeof current_value=="string")
    current_value=current_value.split(/,/);

  for(var i=0; i<values.length; i++) {
    ret+="  <input type='checkbox' name='"+key+"' id='"+values[i]+"' value='"+values[i]+"'";
    if(in_array(values[i], current_value))
      ret+=" checked='checked'";
    ret+="/><label for='"+values[i]+"'>"+t("options:"+key+":"+values[i])+"</label><br/>\n";
  }

  ret+="</p>\n";
  
  return ret;
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

function options_checkbox_get(key) {
  var form=document.getElementById("options_form");
  var ret=[];

  if(!form.elements[key].length) {
    if(form.elements[key].checked)
      ret.push(form.elements[key].value);
  }
  else
    for(var i=0; i<form.elements[key].length; i++) {
      if(form.elements[key][i].checked)
	ret.push(form.elements[key][i].value);
    }

  return ret;
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
