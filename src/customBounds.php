<?php
function ajax_custom_bounds_add($get_param, $post_param) {
  if (!array_key_exists('customBounds', $_SESSION)) {
    $_SESSION['customBounds'] = array();
  }

  $_SESSION['customBounds'][] = $get_param['id'];

  return array('success' => true);
}

function ajax_custom_bounds_remove($get_param, $post_param) {
  if (!array_key_exists('customBounds', $_SESSION)) {
    $_SESSION['customBounds'] = array();
  }

  $p = array_search($get_param, $_SESSION['customBounds']['id']);
  if ($p !== false) {
    array_splice($_SESSION['customBounds'], $p, 1);
  }

  return array('success' => true);
}

if (array_key_exists('customBounds', $_SESSION)) {
  html_export_var(array('customBounds' => $_SESSION['customBounds']));
}
