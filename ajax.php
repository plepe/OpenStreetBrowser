<?php
/**
 * @file ajax.php
 * @brief Most ajax-requests call this file, it calls the specified function.
 */
?>
<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
call_hooks("ajax_start");

function error($msg) {
  /// Do something with this error
}

Header("Content-Type: application/json; charset=UTF-8");

$postdata = file_get_contents("php://input");
if ($postdata) {
  $postdata = json_decode($postdata, true);
}

$fun = "ajax_{$_REQUEST['__func']}";
$return = $fun($_REQUEST, $postdata);

print json_encode($return);
