<?
function state_info_list_request($dom) {
  $req=$dom->getElementsByTagName("request");
  if($req->length==0)
    return;
  $req->item(0)->setAttribute("foo", "bar");
}

register_hook("list_request", "state_info_list_request");
