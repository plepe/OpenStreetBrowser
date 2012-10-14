<?
function ajax_layer_inspect($param, $xml, $post) {
  $new_tiles=json_decode($post);
  $ret=array();

  foreach($new_tiles as $tile) {
    $x=file_get_contents("$tile/status", "r");
    $ret[$tile]=$x;
  }

  return $ret;
}
