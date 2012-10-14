<?
global $lang_preferred_langs;

function lang_preferred_init() {
  global $lang_preferred_langs;

  $lang_preferred_langs=explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  foreach($lang_preferred_langs as $i=>$l) {
    if(preg_match("/^([a-z\-]+);q=([0-9\.]+)$/", $l, $m)) {
      $lang_preferred_langs[$i]=$m[1];
    }
  }

  html_export_var(array(
    "lang_preferred_langs"=>$lang_preferred_langs,
  ));
}

register_hook("init", "lang_preferred_init");
