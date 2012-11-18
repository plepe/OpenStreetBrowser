<?
/**
 * @file ajax.php
 * @brief Most ajax-requests call this file, it calls the specified function.
 */
session_start();
$design_hidden=1;
?>
<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
include "xml.php";
user_check_auth();
call_hooks("ajax_start", null);

function error($msg) {
  /// Do something with this error
}

function build_request($param, $prefix, $ret) {
  if(is_array($param)) {
    foreach($param as $k=>$v) {
      build_request($v, "{$prefix}[$k]", &$ret);
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

Header("Content-Type: text/xml; charset=UTF-8");
//print "<?xml version='1.0' encoding='UTF-8'? >\n";

$postdata = file_get_contents("php://input");

$fun="ajax_$_REQUEST[func]";
//print "<data>\n";
$xml=new DOMDocument();
//$ret=export_formated_text("value", html_var_to_js($fun($_REQUEST["param"], $xml)));
$return=$fun($_REQUEST["param"], $xml, $postdata);

if(!$xml->firstChild) {
  $res=dom_create_append($xml, "result", $xml);
  $ret=dom_create_append($res, "return", $xml);

  $return=json_split(json_encode($return));

  foreach($return as $r) {
    $part=dom_create_append($ret, "part", $xml);
    dom_create_append_text($part, $r, $xml);
  }
}
call_hooks("xml_done", $xml);
print $xml->saveXML();

//print $ret;
//print "</data>\n";
