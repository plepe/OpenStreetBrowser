<?
function state_info_date() {
  $res=sql_query("select tstamp as last_change from nodes where id=(select max(id) from nodes);");
  $elem=pg_fetch_assoc($res);

  $t=new DateTime($elem['last_change']);
  return $t;
}

function state_info_list_request($dom) {
  $req=$dom->getElementsByTagName("request");
  if($req->length==0)
    return;

  if(!($t=state_info_date()))
    return;

  $req->item(0)->setAttribute("state", $t->format("c"));
}

register_hook("list_request", "state_info_list_request");
