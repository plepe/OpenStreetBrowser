<?
function ajax_my_maps_save($param, $xml, $post_data) {
  file_put_contents("/tmp/{$param['id']}.bar", $post_data);

  return true;
}
