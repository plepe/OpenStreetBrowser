<?
function translation_statistics_load_lang($lang) {
  global $plugins_list;

  $lang_str=array();

  @include("lang/{$lang}.php");
  @include("lang/lang_{$lang}.php");
  @include("lang/tags_{$lang}.php");
  foreach($plugins_list as $plugin=>$dummy) {
    @include("plugins/$plugin/lang_{$lang}.php");
  }

  return $lang_str;
}

function translation_statistics_lang($lang) {
  global $language_list;
  global $ui_lang;

  $ret=array();
  $ret['name']=$language_list[$lang];
  $ret["name:$ui_lang"]=lang("lang:$lang");
  $lang_str=translation_statistics_load_lang($lang);
  $ret['base_language']=coalesce($lang_str['base_language'], "en");
  $ret['lang_str_count']=sizeof($lang_str);

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
