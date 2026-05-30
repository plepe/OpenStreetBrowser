<?php
function http_get_contents ($url, $options = []) {
  global $config;

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "{$config['app']['name']} ({$config['app']['url']}; {$config['app']['contact']})");

  return curl_exec($ch);
}
