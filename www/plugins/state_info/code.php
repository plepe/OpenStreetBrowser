<?
function state_info_date() {
  global $root_path;
  $file="$root_path/data/updates/state.txt";

  if(!file_exists($file))
    return;

  $f=fopen($file, "r");
  while($r=fgets($f)) {
    $r=explode("=", trim($r));
    $l[$r[0]]=$r[1];
  }
  fclose($f);

  if(!($t=$l['timestamp']))
    return;
  
  $t=stripslashes($t);
  $t=new DateTime($t);

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
