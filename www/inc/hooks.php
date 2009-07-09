<?
$hooks=array();

function call_hooks($why, $vars, $param1=0, $param2=0, $param3=0, $param4=0) {
  global $hooks;

  if($hooks[$why])
    foreach($hooks[$why] as $h) {
      $h(&$vars, $param1, $param2, $param3, $param4);
    }
}

function register_hook($why, $fun) {
  global $hooks;

  $hooks[$why][]=$fun;
}

//use_javascript("inc/hooks");
