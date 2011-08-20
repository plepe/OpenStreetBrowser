<?
$class_info=0;

class map_key {
  function title() {
    return "Map key";
  }

  function show_info($bounds) {
    global $overlays_show;
    global $class_info;
    $ret=lang("map_key_head")." (".lang("map_key:zoom")." $bounds[zoom])";


    return $ret;
  }

}

function map_key_main_links($links) {
  $links[]=array(-5, "<a href='javascript:map_key_toggle()'>".lang("main:map_key")."</a>");
}

register_hook("main_links", "map_key_main_links");
