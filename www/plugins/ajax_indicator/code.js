function ajax_indicator_dom() {
  var span=document.createElement("span");
  
  var img=dom_create_append(span, "img");
  img.src="img/ajax_loader.gif";

  dom_create_append_text(span, " ");

  dom_create_append_text(span, lang("loading"));

  return span;
}
