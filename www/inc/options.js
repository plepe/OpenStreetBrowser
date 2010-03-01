var options_win;

function set_options() {
  var form=document.getElementById("options_form");

  alert(form.elements.autozoom[0].checked);
}

function close_options() {
  options_win.close();
  delete(options_win);
}

function show_options() {
  var ret;

  options_win=new win("options_win");

  ret ="<form action='javascript:set_options()' id='options_form'>\n";

  ret+="<h4>"+t("options:autozoom")+"</h4>\n";
  ret+="<p><input type='radio' name='autozoom' value='pan'>"+t("options:autozoom:pan")+"</input><br>\n";
  ret+="<input type='radio' name='autozoom' value='move'>"+t("options:autozoom:move")+"</input><br>\n";
  ret+="<input type='radio' name='autozoom' value='stay'>"+t("options:autozoom:stay")+"</input></p>\n";

  ret+="<p><input type='submit' value='"+t("save")+"'>\n";
  ret+="<input type='button' onClick='javascript:close_options()' value='"+t("cancel")+"></p>\n";
  ret+="</form>\n";

  options_win.content.innerHTML=ret;
}
