<?
$class_info=0;
$map_key_list=array();

class map_key {
  function title() {
    return "Map key";
  }

  function show_info($bounds) {
    global $overlays_show;

    return $ret;
  }
}

class map_key_entry {
  public $id;

  function __construct($id) {
    $this->id=$id;

    global $map_key_list;
    $map_key_list[$id]=$this;
  }

  function export() {
    return null;
  }
}

function map_key_main_links($links) {
  $links[]=array(-5, "<a href='javascript:map_key_toggle()'>".lang("main:map_key")."</a>");
}

function map_key_export() {
  global $map_key_list;

  $export=array();
  foreach($map_key_list as $id=>$ob) {
    $export[$id]=array(get_class($ob), $ob->export());
  }

  $export=json_encode($export);
  print "<script type='text/javascript'>\nmap_key_list=$export;\n</script>\n";
}

function ajax_map_key($param) {
  $list=array();
  call_hooks("map_key", &$list, $param);

  foreach($list as $i=>$l) {
    $list[$i]=array($l[0], $l[1]->show_info($param), get_class($l[1]));
  }

  return array("param"=>$param, "list"=>$list);
/*  $mapkey=new map_key();
  $ret=$mapkey->show_info($param);
  $text=$xml->createTextNode($ret);

  $ret=$xml->createElement("result");
  $value=$xml->createElement("text");
  $value->appendChild($text);
  $ret->appendChild($value);
  $xml->appendChild($ret);

  $value=$xml->createElement("zoom");
  $ret->appendChild($value);
  $value->setAttribute("value", $param[zoom]);

  if($param[overlays])
    foreach($param[overlays] as $p=>$dummy) {
      $value=$xml->createElement("overlay");
      $ret->appendChild($value);
      $value->setAttribute("value", $p);
    } */

}

register_hook("main_links", "map_key_main_links");
//register_hook("html_done", "map_key_export");
