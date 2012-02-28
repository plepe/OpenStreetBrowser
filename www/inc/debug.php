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
  global $debug_levels_abbr;
  global $www_debug_level;

  $result=$xml->getElementsByTagname("result");
  $result=$result->item(0);

  if(!isset($www_debug_level))
    $www_debug_level=2;

  if($debug_list)
  foreach($debug_list as $entry) {
    if($entry['level']<$www_debug_level)
      continue;

    $debug=$xml->createElement("debug");
    $text=$xml->createTextNode(sprintf("%s (%s) %s %s",
	Date("Y-m-d H:i:s", $entry['time']),
	$debug_levels_abbr[$entry['level']],
	$entry['category'],
	$entry['text']
      ));
    $debug->appendChild($text);
    $result->appendChild($debug);
  }
}

register_hook("xml_done", "debug_write");
