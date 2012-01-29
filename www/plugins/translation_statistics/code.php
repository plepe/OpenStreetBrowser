<?
function translation_statistics_lang($lang) {
  global $language_list;
  global $ui_lang;

  $ret=array();
  $ret['name']=$language_list[$lang];
  $ret["name:$ui_lang"]=lang("lang:$lang");

  return $ret;
}

function ajax_translation_statistics() {
  global $ui_langs;
  $ret=array();

  foreach($ui_langs as $lang) {
    $ret[$lang]=translation_statistics_lang($lang);
  }
  
  return $ret;
}
