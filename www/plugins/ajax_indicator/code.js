/** create an Ajax Indicator
 * Options:
 * ajax_action object (optional) the running ajax action
 */
function ajax_indicator_dom(ajax_action) {
  var span=document.createElement("span");
  
  var img=dom_create_append(span, "img");
  img.src="img/ajax_loader.gif";

  dom_create_append_text(span, " ");

  dom_create_append_text(span, lang("loading"));

  return span;
}
