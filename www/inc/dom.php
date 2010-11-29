<?
function dom_create_append($parent, $type, $xml) {
  if(!$xml) {
    print "dom_create_append: No root xml defined\n";
    return;
  }

  if(!$parent) {
    print "dom_create_append: No parent defined\n";
    return;
  }

  $x=$xml->createElement($type);
  $parent->appendChild($x);
  return $x;
}

function dom_create_append_text($parent, $text, $xml) {
  if(!$xml) {
    print "dom_create_append_text: No root xml defined\n";
    return;
  }

  if(!$parent) {
    print "dom_create_append_text: No parent defined\n";
    return;
  }

  $x=$xml->createTextNode($text);
  $parent->appendChild($x);
  return $x;
}

function dom_clean($parent) {
  if(!$xml) {
    print "dom_clean: No root xml defined\n";
    return;
  }

  if(!$parent) {
    print "dom_clean: No parent defined\n";
    return;
  }

  while($parent->firstChild)
    $parent->removeChild($parent->firstChild);
}
