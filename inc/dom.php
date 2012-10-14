<?
function dom_create_append($parent, $type, $xml=null) {
  if(!$parent) {
    print "dom_create_append: No parent defined\n";
    return;
  }

  if($xml==null) {
    if(get_class($parent)=="DOMDocument")
      $xml=$parent;
    else
      $xml=$parent->ownerDocument;
  }

  $x=$xml->createElement($type);
  $parent->appendChild($x);
  return $x;
}

function dom_create_append_text($parent, $text, $xml=null) {
  if(!$parent) {
    print "dom_create_append_text: No parent defined\n";
    return;
  }

  if($xml==null) {
    if(get_class($parent)=="DOMDocument")
      $xml=$parent;
    else
      $xml=$parent->ownerDocument;
  }

  $x=$xml->createTextNode($text);
  $parent->appendChild($x);
  return $x;
}

function dom_clean($parent) {
  if(!$parent) {
    print "dom_clean: No parent defined\n";
    return;
  }

  if($xml==null) {
    if(get_class($parent)=="DOMDocument")
      $xml=$parent;
    else
      $xml=$parent->ownerDocument;
  }

  while($parent->firstChild)
    $parent->removeChild($parent->firstChild);
}
