<?
function dom_create_append($parent, $type, $xml) {
  $x=$xml->createElement($type);
  $parent->appendChild($x);
  return $x;
}

function dom_create_append_text($parent, $text, $xml) {
  $x=$xml->createTextNode($text);
  $parent->appendChild($x);
  return $x;
}

function dom_clean($parent) {
  while($parent->firstChild)
    $parent->removeChild($parent->firstChild);
}
