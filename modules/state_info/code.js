var state_info_date;

function state_info_create_span() {
  var licence=document.getElementById("licence");
  i=licence.firstChild;
  while(i) {
    if(i.href&&i.href.match("creativecommons")) {
      state_info_date=document.createElement("span");
      state_info_date.id="state_info_date";
      licence.insertBefore(state_info_date, i.nextSibling);
    }

    i=i.nextSibling;
  }
}

function state_info_list_receive(data) {
  var request;
  if(request=data.getElementsByTagName("request"))
    request=request[0];

  if(!request)
    return;

  var t=request.getAttribute("state");
  if(!t)
    return;

  t=new Date(t);

  if(!state_info_date)
    state_info_create_span();

  dom_clean(state_info_date);
  dom_create_append_text(state_info_date, " "+(1900+t.getYear())+"-"+(t.getMonth()+1)+"-"+t.getDate());
}

register_hook("list_receive", state_info_list_receive);
