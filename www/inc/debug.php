<?
unset($debug_list);

function debug($text, $category) {
  global $debug_list;

  $debug_list[]="$category: $text";
}

function debug_write($xml) {
  global $debug_list;
  $result=$xml->getElementsByTagname("result");
  $result=$result->item(0);

  if($debug_list)
  foreach($debug_list as $d) {
    $debug=$xml->createElement("debug");
    $text=$xml->createTextNode($d);
    $debug->appendChild($text);
    $result->appendChild($debug);
  }
}

register_hook("xml_done", "debug_write");
