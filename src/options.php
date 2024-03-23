<?php
function ajax_options_save($get_param, $postdata) {
  $post_param = json_decode($postdata, true);

  call_hooks('options_save', $post_param);

  $_SESSION['options'] = $post_param;

  return array('success' => true, 'options' => $_SESSION['options']);
}

function ajax_options_save_key ($get_param, $postdata) {
  $kv = json_decode($postdata, true);

  if ($kv['value'] === null) {
    unset($_SESSION['options'][$kv['key']]);
  } else {
    $_SESSION['options'][$kv['key']] = $kv['value'];
  }

  call_hooks('options_save', $_SESSION['options']);

  return array('success' => true, 'options' => $_SESSION['options']);
}

function ajax_options_save_key_array_add ($get_param, $postdata) {
  $kv = json_decode($postdata, true);

  if (!array_key_exists($kv['option'], $_SESSION['options'])) {
    $_SESSION['options'][$kv['option']] = [];
  }

  $_SESSION['options'][$kv['option']][] = $kv['element'];

  call_hooks('options_save', $_SESSION['options']);

  return array('success' => true, 'options' => $_SESSION['options']);
}

function ajax_options_save_key_array_remove ($get_param, $postdata) {
  $kv = json_decode($postdata, true);

  if (!array_key_exists($kv['option'], $_SESSION['options'])) {
    $_SESSION['options'][$kv['option']] = [];
  }

  $pos = array_search($kv['element'], $_SESSION['options'][$kv['option']]);
  if ($pos !== false) {
    array_splice($_SESSION['options'][$kv['option']], $pos, 1);
  }

  call_hooks('options_save', $_SESSION['options']);

  return array('success' => true, 'options' => $_SESSION['options']);
}

if (!array_key_exists('options', $_SESSION)) {
  $_SESSION['options'] = array();
}

html_export_var(array('options' => $_SESSION['options']));
