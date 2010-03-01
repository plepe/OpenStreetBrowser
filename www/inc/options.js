var options_win;
var options_list=[ "autozoom" ];
var options_values={};

function options_set(key, value) {
  var expiry=new Date();
  expiry.setTime(expiry.getTime()+365*86400000);

  document.cookie="option:"+key+"="+value+"; expires="+expiry.toGMTString()+"; path=/";
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
}

function close_options() {
  options_win.close();
  delete(options_win);
}

function options_radio(key, current_value, values) {
  var ret="";

  ret+="<h4>"+t("options:"+key)+"</h4>\n";
  ret+="<p>\n";

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

function options_radio_get(key) {
  var form=document.getElementById("options_form");

  for(var i=0; i<form.elements[key].length; i++) {
    if(form.elements[key][i].checked)
      return form.elements[key][i].value;
  }

  return null;
}

function show_options() {
  var ret;

  options_win=new win("options_win");

  ret ="<form action='javascript:save_options()' id='options_form'>\n";

  ret+=options_radio("autozoom", "move", [ "pan", "move", "stay" ]);

  ret+="<p><input type='submit' value='"+t("save")+"'>\n";
  ret+="<input type='button' onClick='javascript:close_options()' value='"+t("cancel")+"></p>\n";
  ret+="</form>\n";

  options_win.content.innerHTML=ret;
}

options_load();
