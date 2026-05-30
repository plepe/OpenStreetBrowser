<?php
function http_get_contents ($url, $options = []) {
  global $config;

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "{$config['app']['name']} ({$config['app']['url']}; {$config['app']['contact']})");

  $result = curl_exec($ch);

  $request = curl_getinfo($ch);
  if ($request['http_code'] === 301 && ($options['redirect_count'] ?? 0) < 5) {
    $options['redirect_count'] = ($options['redirect_count'] ?? 0) + 1;
    return http_get_contents($request['redirect_url'], $options);
  }

  Header("HTTP/1.1 {$request['http_code']}");
  Header("Content-Type: {$request['content_type']}");
  Header("Content-Length: {$request['download_content_length']}");

  return $result;
}
