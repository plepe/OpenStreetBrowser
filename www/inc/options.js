var options_win;

function set_options() {
  var form=document.getElementById("options_form");

  alert(form.elements.autozoom[0].checked);
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

function show_options() {
  var ret;

  options_win=new win("options_win");

  ret ="<form action='javascript:set_options()' id='options_form'>\n";

  ret+=options_radio("autozoom", "move", [ "pan", "move", "stay" ]);

  ret+="<p><input type='submit' value='"+t("save")+"'>\n";
  ret+="<input type='button' onClick='javascript:close_options()' value='"+t("cancel")+"></p>\n";
  ret+="</form>\n";

  options_win.content.innerHTML=ret;
}
