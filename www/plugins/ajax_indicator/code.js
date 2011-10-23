/** create an Ajax Indicator
 * Options:
 * ajax_action object (optional) the running ajax action
 */
function ajax_indicator(ajax_action) {
  this.ajax_action=ajax_action;
}

// ajax_indicator.dom()
ajax_indicator.prototype.dom=function() {
  var span=document.createElement("span");
  span.className="ajax_indicator";
  
  var img=dom_create_append(span, "img");
  img.src="img/ajax_loader.gif";

  dom_create_append_text(span, " ");

  dom_create_append_text(span, lang("loading"));

  return span;
}

/**
 * create ajax_indicator and return a dom of it
 */
function ajax_indicator_dom(ajax_action) {
  var ai=new ajax_indicator(ajax_action);
  return ai.dom();
}
