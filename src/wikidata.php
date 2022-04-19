<?php
$wikidataCache = array();

function wikidataLoad ($id) {
  global $wikidataCache;

  if (!array_key_exists($id, $wikidataCache)) {
    $body = file_get_contents("https://www.wikidata.org/wiki/Special:EntityData/{$id}.json");
    $body = $body ? json_decode($body, true) : false;

    if (!array_key_exists('entities', $body) || !array_key_exists($id, $body['entities'])) {
      return false;
    }

    $wikidataCache[$id] = $body['entities'][$id];
  }

  return $wikidataCache[$id];
}

function wikidataGetLabel ($id, $lang) {
  $data = wikidataLoad($id);

  if (array_key_exists($lang, $data['labels'])) {
    return $data['labels'][$lang]['value'];
  }
  elseif (array_key_exists('en', $data['labels'])) {
    return $data['labels']['en']['value'];
  }
  elseif (!sizeof($data['labels'])) {
    return $id;
  }
  else {
    return array_values($data['labels'])[0]['value'];
  }
}
