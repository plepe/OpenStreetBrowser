<?
function translation_statistics_load_tags_missing() {
  $list=array();

  $f=fopen("lang/tags_en.php", "r");
  while($r=fgets($f)) {
    if(preg_match("/^#\\\$lang_str\[\"([^\"]*)\"\]/", $r, $m)) {
      $list[]=$m[1];
    }
  }

  return $list;
}

function translation_statistics_load_tags($lang) {
  global $plugins_list;

  $lang_str=array();

  @include("lang/tags_{$lang}.php");

  return $lang_str;
}

function translation_statistics_load_lang($lang) {
  global $plugins_list;

  $lang_str=array();

  @include("lang/{$lang}.php");
  @include("lang/lang_{$lang}.php");
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

  $lang_str=translation_statistics_load_tags($lang);
  $ret['tags_count']=sizeof($lang_str);

  return $ret;
}

function ajax_translation_statistics() {
  global $ui_langs;
  $ret=array();

  $ret['total']=array(
    "lang_str_count"=>sizeof(translation_statistics_load_lang("en")),
    "tags_count"=>sizeof(translation_statistics_load_tags("en"))+sizeof(translation_statistics_load_tags_missing()),
  );

  foreach($ui_langs as $lang) {
    $ret[$lang]=translation_statistics_lang($lang);
  }
  
  return $ret;
}
