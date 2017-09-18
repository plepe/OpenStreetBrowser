<?php
function ajax_wikimedia ($param) {
  $ret = array();

  $wm_url = "https://commons.wikimedia.org/wiki/" . urlencode(strtr($param['page'], array(" " => "_")));

  $content = file_get_contents($wm_url);

  $dom = new DOMDocument();
  $dom->loadHTML($content);

  $uls = $dom->getElementsByTagName('ul');//interlanguage-link interwiki-bar');
  for ($i = 0; $i < $uls->length; $i++) {
    $ul = $uls->item($i);

    if ($ul->getAttribute('class') === 'gallery mw-gallery-traditional') {
      $imgs = $ul->getElementsByTagName('img');

      for ($j = 0; $j < $imgs->length; $j++) {
        $ret[] = $imgs->item($j)->getAttribute('alt');
      }
    }
  }

  return array(
    'images' => $ret,
  );
}
