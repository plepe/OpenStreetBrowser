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

  $content = file_get_contents($wp_url);

  $langList = array($wp_lang => $wp_url);

  $dom = new DOMDocument();
  $dom->loadHTML($content);

  $langDiv = $dom->getElementsByTagName('li');//interlanguage-link interwiki-bar');
  for ($i = 0; $i < $langDiv->length; $i++) {
    $li = $langDiv->item($i);

    if (preg_match('/^interlanguage-link interwiki-([a-z\-]+)( .*|)$/', $li->getAttribute('class'), $m)) {
      $a = $li->firstChild;
      $langList[$m[1]] = $a->getAttribute('href');
    }
  }

  if ($wp_lang !== $param['lang'] && array_key_exists($param['lang'], $langList)) {
    $content = file_get_contents($langList[$param['lang']]);
    $wp_lang = $param['lang'];
  }

  return array(
    'content' => $content,
    'languages' => $langList,
    'language' => $wp_lang,
  );
}
