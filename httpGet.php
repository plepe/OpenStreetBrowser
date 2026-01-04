<?php
require('conf.php');
require('modulekit.php');

$ch = curl_init($_REQUEST['url']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, [
  "{$config['app']['name']}/{$version} ({$config['app']['url']}; {$config['app']['contact']})",
]);
print curl_exec($ch);
