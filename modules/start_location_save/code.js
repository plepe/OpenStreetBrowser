function start_location_save_start(start) {
  alert(start);
}

function start_location_save_activate(form) {
  var div=document.getElementById("start_location_list");

  var input=dom_create_append(div, "input");
  input.type="radio";
  input.name="start_value";
  input.value="saved";

  var select=dom_create_append(div, "select");
  select.name="start_value_saved";

  var option=dom_create_append(select, "option");
  option.value="foo";
  dom_create_append_text(option, "bar");

  var br=dom_create_append(div, "br");
}

register_hook("start_location_activate", start_location_save_activate);
register_hook("start_location_start", start_location_save_start);
