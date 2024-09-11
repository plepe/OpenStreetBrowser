<?php
function ajax_ImageLoaderWikimediaCategoryList ($param) {
  $ret = array();
  $retData = array();

  $wm_url = "https://commons.wikimedia.org/w/index.php?title=" . urlencode(strtr($param['page'], array(" " => "_")));

  if (isset($param['continue'])) {
    $wm_url .= "&filefrom=" . urlencode(strtr($param['continue'], array(" " => "_")));
  }

  $content = file_get_contents($wm_url);

  $dom = new DOMDocument();
  $dom->loadHTML($content);

  $uls = $dom->getElementsByTagName('ul');//interlanguage-link interwiki-bar');
  for ($i = 0; $i < $uls->length; $i++) {
    $ul = $uls->item($i);

    if ($ul->getAttribute('class') === 'gallery mw-gallery-traditional') {
      $imgs = $ul->getElementsByTagName('img');

      for ($j = 0; $j < $imgs->length; $j++) {
        $item = $imgs->item($j);
        $srcPath = explode('/', $item->getAttribute('src'));

        if (sizeof($srcPath) < 2) {
          continue;
        }

        $id = urldecode($srcPath[sizeof($srcPath) - 2]);

        $ret[] = $id;
        $retData[] = array(
          'id' => $id,
          'width' => $item->getAttribute('data-file-width'),
          'height' => $item->getAttribute('data-file-height'),
        );
      }
    }
  }

  $continue = false;
  $as = $dom->getElementsByTagName('a');
  for ($i = 0; $i < $as->length; $i++) {
    $a = $as->item($i);

    if (preg_match("/^\/w\/index.php\?title=(.*)&filefrom=([^#]+)#mw-category-media$/", $a->getAttribute('href'), $m)) {
      $continue = $m[2];
    }
  }

  return array(
    'images' => $ret, // deprecated as of 2017-09-27
    'imageData' => $retData,
    'continue' => $continue,
  );
}
