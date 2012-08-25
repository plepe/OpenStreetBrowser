<?
function translation_statistics_load_tags_missing() {
  global $translation_path;
  $list=array();

  $f=fopen("$translation_path/www/lang/tags_en.php", "r");
  while($r=fgets($f)) {
    if(preg_match("/^#\\\$lang_str\[\"([^\"]*)\"\]/", $r, $m)) {
      $list[]=$m[1];
    }
  }

  return $list;
}

function translation_statistics_load_tags($lang) {
  global $plugins_list;
  global $translation_path;

  $lang_str=array();

  @include("$translation_path/www/lang/tags_{$lang}.php");

  return $lang_str;
}

function translation_statistics_load_lang($lang) {
  global $plugins_list;
  global $translation_path;

  $lang_str=array();

  @include("$translation_path/www/lang/{$lang}.php");
  @include("$translation_path/www/lang/lang_{$lang}.php");
  foreach($plugins_list as $plugin=>$dummy) {
    @include("$translation_path/www/plugins/$plugin/lang_{$lang}.php");
  }

  return $lang_str;
}

function translation_statistics_category_total() {
  $list=array();

  $sql_str="select category.* from category_current left join category on category_current.version=category.version";
  $res=sql_query($sql_str);
  while($elem=pg_fetch_assoc($res)) {
    $tags=new tags(parse_hstore($elem['tags']));

    $list["category:{$elem['category_id']}:name"]=true;
    $list["category:{$elem['category_id']}:description"]=true;

    $sql_str="select * from category_rule where version='{$elem['version']}'";
    $res_r=sql_query($sql_str);
    while($elem_r=pg_fetch_assoc($res_r)) {
      $list["category:{$elem['category_id']}:{$elem_r['rule_id']}:name"]=true;
    }
  }

  return $list;
}

function translation_statistics_category_lang($lang) {
  $list=array();

  $sql_str="select category.* from category_current left join category on category_current.version=category.version";
  $res=sql_query($sql_str);
  while($elem=pg_fetch_assoc($res)) {
    $tags=new tags(parse_hstore($elem['tags']));
    $cat_lang=coalesce($tags->get("lang"), "en");

    if(($s=$tags->get("name:$lang"))||(($s=$tags->get("name"))&&$lang==$cat_lang))
      $list["category:{$elem['category_id']}:name"]=$s;
    if(($s=$tags->get("description:$lang"))||(($s=$tags->get("description"))&&$lang==$cat_lang))
    $list["category:{$elem['category_id']}:description"]=$s;

    $sql_str="select * from category_rule where version='{$elem['version']}'";
    $res_r=sql_query($sql_str);
    while($elem_r=pg_fetch_assoc($res_r)) {
      $tags_r=new tags(parse_hstore($elem_r['tags']));

      if(($s=$tags_r->get("name:$lang"))||(($s=$tags_r->get("name"))&&$lang==$cat_lang))
	$list["category:{$elem['category_id']}:{$elem_r['rule_id']}:name"]=$s;
    }
  }

  return $list;
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

  $lang_str=translation_statistics_category_lang($lang);
  $ret['category_count']=sizeof($lang_str);

  return $ret;
}

function ajax_translation_statistics() {
  global $ui_langs;
  $ret=array();

  translation_init();

  $ret['total']=array(
    "lang_str_count"=>sizeof(translation_statistics_load_lang("en")),
    "tags_count"=>sizeof(translation_statistics_load_tags("en"))+sizeof(translation_statistics_load_tags_missing()),
    "category_count"=>sizeof(translation_statistics_category_total()),
  );

  foreach($ui_langs as $lang) {
    $ret[$lang]=translation_statistics_lang($lang);
  }
  
  return $ret;
}
