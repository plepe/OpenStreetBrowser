<?php
function ajax_options_save($get_param, $post_param) {
  call_hooks('options_save', $post_param);

  return array('success' => true);
}
