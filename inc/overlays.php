<?
$overlays_list=array();
$overlays_show=array();

function register_overlay($path, $list) {
  global $overlays_list;

  $overlays_list[implode("|", $path)]=$list;
}

function html_done_overlay() {
  global $overlays_list;

  html_export_var(array("overlays_list"=>$overlays_list));
}

function show_overlay($id) {
  global $overlays_show;
  $overlays_show[]=$id;
}

function xml_done_overlay($xml) {
  global $overlays_show;

  $ob=$xml->getElementsByTagName("result");
  $ob=$ob->item(0);

  foreach($overlays_show as $s) {
    $n=$xml->createElement("overlay");
    $n->setAttribute("id", $s);
    $ob->appendChild($n);
  }
}

function show_list_overlay($top_type, $next_type) {
  global $overlays_list;

  if($overlays_list["$top_type"])
    show_overlay($overlays_list["$top_type"]);
  if($overlays_list["$top_type|$next_type"])
    show_overlay($overlays_list["$top_type|$next_type"]);
}

register_hook("html_done", html_done_overlay);
register_hook("xml_done", xml_done_overlay, $xml);
