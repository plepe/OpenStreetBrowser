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

function wikidataGetValues ($id, $property) {
  $data = wikidataLoad($id);

  if (!array_key_exists($property, $data['claims'])) {
    return [];
  }

  return array_map(
    function ($el) {
      return $el['mainsnak']['datavalue']['value'];
    },
    $data['claims'][$property]
  );
}

function wikidataFormatDate($value, $maxPrecision = 13) {
  $v = new DateTime($value['time']);
  $p = min($maxPrecision, $value['precision']);

  if ($p < 9) {
  } else {
    $formats = [
      9 => 'Y',
      10 => 'M Y',
      11 => 'j. M Y',
      12 => 'j. M Y - G:00',
      13 => 'j. M Y - G:i',
      14 => 'j. M Y - G:i:s',
    ];

    return $v->format($formats[$p]);
  }
}

function wikidataFormat ($id, $lang) {
  $ret = '<b>' . wikidataGetLabel($id, $lang) . '</b>';

  $birthDate = wikidataGetValues($id, 'P569');
  $deathDate = wikidataGetValues($id, 'P570');
  if (sizeof($birthDate) && sizeof($deathDate)) {
    $ret .= ' (' . wikidataFormatDate($birthDate[0], 11) . ' — ' . wikidataFormatDate($deathDate[0], 11) . ')';
  }
  elseif (sizeof($birthDate)) {
    $ret .= ' (* ' . wikidataFormatDate($birthDate[0], 11) . ')';
  }
  elseif (sizeof($deathDate)) {
    $ret .= ' († ' . wikidataFormatDate($birthDate[0], 11) . ')';
  }

  $occupation = wikidataGetValues($id, 'P106');
  if (sizeof($occupation)) {
    $ret .= ', ' . implode(', ', array_map(
      function ($value) use ($lang) {
        return wikidataGetLabel($value['id'], $lang);
      },
      $occupation
    ));
  }

  return $ret;
}
