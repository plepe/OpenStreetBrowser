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
