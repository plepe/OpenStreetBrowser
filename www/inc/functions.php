<?
// weight_sort(arr)
// Parameters:
// arr ... an array of form array( array( weight, var ), ... )
//       array( array( -3, A ), array( -1, B ), array( 5, C ), array( -1, D ) )
//         
// Returns:
// An array sorted by the weight of the source, e.g.
//         array( A, B, D, C )
//
// Notes:
// Entries in the source array with the same weight are returned in the
// same order
function weight_sort($arr) {
  $ret1=array();

  // first put all elements into an assoc. array
  foreach($arr as $cur) {
    $wgt=$cur[0];
    $ret1[$wgt][]=$cur[1];
  }

  // get the keys, convert to value, order them
  ksort($ret1);
  $ret2=array();

  // iterate through array and compile final return value
  foreach($ret1 as $cur) {
    for($j=0; $j<sizeof($cur); $j++) {
      $ret2[]=$cur[$j];
    }
  }

  return $ret2;
}

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

