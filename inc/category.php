<?php
function get_category($id, $param) {
  $ret = call_hooks("get_category", $id, $param);

  if(!sizeof($ret))
    return null;

  return $ret[0];
}

function ajax_get_category($param) {
  $ob = get_category($param['id'], $param['param']);
  if(!$ob)
    return null;

  return $ob->data();
}
