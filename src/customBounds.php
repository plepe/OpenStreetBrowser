<?php
function ajax_custom_bounds_add($get_param, $post_param) {
  return ajax_custom_bounds_update($get_param, $post_param);
}

function ajax_custom_bounds_update($get_param, $post_param) {
  if (!array_key_exists('customBounds', $_SESSION)) {
    $_SESSION['customBounds'] = array();
  }

  if (!array_key_exists($get_param['id'], $_SESSION['customBounds'])) {
    $_SESSION['customBounds'][$get_param['id']] = array();
  }

  foreach ($get_param as $k => $v) {
    if ($k !== 'id' && $k !== '__func') {
      $_SESSION['customBounds'][$get_param['id']][$k] = $v;
    }
  }

  return array('success' => true);
}

function ajax_custom_bounds_remove($get_param, $post_param) {
  if (!array_key_exists('customBounds', $_SESSION)) {
    $_SESSION['customBounds'] = array();
  }

  unset($_SESSION['customBounds'][$get_param]);

  return array('success' => true);
}

if (array_key_exists('customBounds', $_SESSION)) {
  html_export_var(array('customBounds' => $_SESSION['customBounds']));
}
