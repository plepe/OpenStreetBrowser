<?php
register_hook('ajax_start', function () {
  global $db;
  global $db_conf;
  
  if ($db_conf) {
    $db = new PDO($db_conf['dsn'], $db_conf['username'], $db_conf['password']);
  }
});
