<?php
function overpass_query($query) {
  global $overpass;

  $url = "{$overpass['url']}/interpreter";

  $req = curl_init($url);
  curl_setopt($req, CURLOPT_POST, 1);
  curl_setopt($req, CURLOPT_POSTFIELDS, "data=" . urlencode($query));
  curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($req);

  $result = json_decode($result, 1);

  return($result['elements']);
}
