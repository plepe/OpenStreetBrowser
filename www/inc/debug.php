<?
unset($debug_list);

function debug($text, $category="unknown") {
  global $debug_list;

  $debug_list[]=array(time(), $category, $text);
  call_hooks("debug", $text, $category);
}

function debug_write($xml) {
  global $debug_list;
  $result=$xml->getElementsByTagname("result");
  $result=$result->item(0);

  if($debug_list)
  foreach($debug_list as $d) {
    $debug=$xml->createElement("debug");
    $text=$xml->createTextNode(Date("Y-m-d H:i:s", $d[0])." {$d[1]}: {$d[2]}");
    $debug->appendChild($text);
    $result->appendChild($debug);
  }
}

register_hook("xml_done", "debug_write");
