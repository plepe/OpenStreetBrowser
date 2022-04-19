<?php
function ajax_wikipedia ($param) {
  if (preg_match("/^([^:]+):(.*)$/", $param['page'], $m)) {
    $wp_lang = $m[1];
    $wp_page = $m[2];
  }
  elseif (preg_match("/^Q\d+$/", $param['page'])) {
    $data = wikidataLoad($param['page']);

    if (array_key_exists('sitelinks', $data)) {
      $sitelinks_ids = array_keys($data['sitelinks']);
      $sitelinks_ids = array_values(array_filter($sitelinks_ids, function ($id) {
        return preg_match('/wiki$/', $id) && $id !== 'commonswiki';
      }));

      if (array_key_exists($param['lang'] . 'wiki', $data['sitelinks'])) {
        $wp_lang = $param['lang'];
        $wp_url = $data['sitelinks'][$param['lang'] . 'wiki']['url'];
      }
      elseif (array_key_exists('enwiki', $data['sitelinks'])) {
        $wp_lang = 'en';
        $wp_url = $data['sitelinks']['enwiki']['url'];
      }
      elseif (sizeof($sitelinks_ids)) {
        $id = $sitelinks_ids[0];
        $wp_lang = substr($id, 0, strlen($id) - 4);
        $wp_url = $data['sitelinks'][$id]['url'];
      }
      else {
        $content = "<div><div id='mw-content-text'><div><p>" . wikidataFormat($param['page'], $param['lang']) . "</p></div></div></div>";
        $url = "https://wikidata.org/wiki/{$param['page']}";
        if (array_key_exists('commonswiki', $data['sitelinks'])) {
          $url = $data['sitelinks']['commonswiki']['url'];
        }

        return array(
          'content' => $content,
          'languages' => [
            $param['lang'] => $url,
          ],
          'language' => $param['lang'],
        );
      }
    }
  }

  if (!isset($wp_lang) || !(isset($wp_page) || isset($wp_url))) {
    return false;
  }

  if (!isset($wp_url)) {
    $wp_url = "https://{$wp_lang}.wikipedia.org/wiki/" . urlencode(strtr($wp_page, array(" " => "_")));
  }

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
