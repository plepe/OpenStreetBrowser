<?
unset($debug_list);

define('D_ERROR', 3);
define('D_WARNING', 2);
define('D_NOTICE', 1);
define('D_DEBUG', 0);

$debug_levels=array(
  D_DEBUG	=>"debug",
  D_NOTICE	=>"notice",
  D_WARNING	=>"warning",
  D_ERROR	=>"error",
);

$debug_levels_abbr=array(
  D_DEBUG	=>"D",
  D_NOTICE	=>"N",
  D_WARNING	=>"W",
  D_ERROR	=>"E",
);

function debug($text, $category="unknown", $level=D_NOTICE) {
  global $debug_list;

  $debug_entry=array(
    'time'	=>time(),
    'category'	=>$category,
    'text'	=>$text,
    'level'	=>$level,
  );

  $debug_list[]=$debug_entry;
  call_hooks("debug", &$debug_entry);
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
