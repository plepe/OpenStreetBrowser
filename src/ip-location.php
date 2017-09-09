<?php
use GeoIp2\Database\Reader;

register_hook('init', function () {
  global $config;

  if (isset($config['checkIpLocation']) && !$config['checkIpLocation']) {
    return;
  }

  $reader = new Reader('data/GeoIP/GeoLite2-City.mmdb');

  try {
    $record = $reader->city($_SERVER['REMOTE_ADDR']);

    $config['defaultView']['lat'] = $record->location->latitude;
    $config['defaultView']['lon'] = $record->location->longitude;
    $config['defaultView']['zoom'] = 10;
  }
  catch (Exception $e) {
    // ignore error
    trigger_error("Can't resolve IP address: " . $e->getMessage(), E_USER_WARNING);
  }
});
