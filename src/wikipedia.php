<?php
function ajax_wikipedia ($param) {
  if (preg_match("/^([^:]+):(.*)$/", $param['page'], $m)) {
    $wp_lang = $m[1];
    $wp_page = $m[2];
  }

  if (!isset($wp_lang) || !isset($wp_page)) {
    return false;
  }

  $wp_url = "https://{$wp_lang}.wikipedia.org/wiki/" . urlencode(strtr($wp_page, array(" " => "_")));

  return array(
    'content' => file_get_contents($wp_url),
  );
}
