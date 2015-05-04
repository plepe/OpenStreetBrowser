<?
/**
 * @file ajax_json.php
 * @brief Most ajax-requests call this file, it calls the specified function.
 */
session_start();
$design_hidden=1;
?>
<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
user_check_auth();
call_hooks("ajax_json_start");

function error($msg) {
  /// Do something with this error
}

function build_request($param, $prefix, &$ret) {
  if(is_array($param)) {
    foreach($param as $k=>$v) {
      build_request($v, "{$prefix}[$k]", $ret);
    }
  }
  else {
    $param=strtr($param, array("#"=>"%23", "'"=>"%27"));
    array_push($ret, "$prefix=$param");
  }
}

function ajax_bla($bla) {
  return array("foo"=>array(1, 2, "ein text"), "bla", $bla);
}

Header("Content-Type: text/javascript; charset=UTF-8");

$param_post = file_get_contents("php://input");
$param_post = json_decode($param_post, true);

$fun="ajax_{$_REQUEST['func']}";

$return = call_user_func($fun, $_REQUEST["param"], $param_post);
$return = json_readable_encode($return);

call_hooks("ajax_json_done", $return);

print $return;
