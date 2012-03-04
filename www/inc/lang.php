<?
include "lang/list.php";
define("F", 1);
define("M", 2);
define("N", 3);
$lang_genders=array(1=>"F", 2=>"M", 3=>"N");

function lang() {
  global $lang_str;
  $offset=1;

  $key=func_get_arg(0);
  if((sizeof(func_get_args())>1)&&is_integer(func_get_arg(1))) {
    $offset++;
    $count=func_get_arg(1);
  }
  else
    $count=1;
  $params=array_slice(func_get_args(), $offset);

  ereg("^(.*)/(.*)$", $key, $m);
  $key_exp=explode(";", $m[2]);
  if(sizeof($key_exp)>1) {
    foreach($key_exp as $key_index=>$key_value) {
      $key_exp[$key_index]=lang("$m[1]/$key_value", $count);
    }
    $l=implode(", ", $key_exp);
  }
  elseif(!isset($lang_str[$key])) {
    if((preg_match("/^tag:([^=]*)=(.*)$/", $key, $m))&&($k=$lang_str["tag:*={$m[2]}"])) {
      // Boolean values, see:
      // http://wiki.openstreetmap.org/wiki/Proposed_features/boolean_values
      $key=$k;
    }
    else if(preg_match("/^tag:([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/", $key, $m)) {
      $key=$m[3];
    }
    elseif(preg_match("/^tag:([^><=!]*)$/", $key, $m)) {
      $key=$m[1];
    }


    return $key.(sizeof($params)?" ".implode(", ", $params):"");
  }
  else {
    $l=$lang_str[$key];
  }

  if(is_array($l)&&(sizeof($l)==1)) {
    $l=$l[0];
  }
  elseif(is_array($l)) {
    if(($count===0)||($count>1))
      $i=1;
    else
      $i=0;

    // if a Gender is defined, shift values
    if(is_integer($l[0]))
      $i++;

    $l=$l[$i];
  }

  return vsprintf($l, $params);
}

// replace all {...} by their translations
function lang_parse($str, $count=0) {
  $ret=$str;

  while(preg_match("/^(.*)\{([^\}]+)\}(.*)$/", $ret, $m)) {
    $args=func_get_args();
    $args[0]=$m[2];

    $ret=$m[1].call_user_func_array("lang", $args).$m[3];
  }

  return $ret;
}

function lang_from_browser($avail_langs=null) {
  $max_q=-1;
  $chosen_lang="";
  $acc_langs=explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);

  foreach($acc_langs as $acc_lang) {
    $acc_lang=explode(";", $acc_lang);
    
    foreach($acc_lang as $acc_lang_part) {
      if(preg_match("/^(.*)=(.*)$/", $acc_lang_part, $m)) {
	$acc_lang[$m[1]]=$m[2];
      }
    }
    if(!$acc_lang['q'])
      $acc_lang['q']=1;

    if(((!$avail_langs)||(in_array($acc_lang[0], $avail_langs)))
       &&(!strpos($acc_lang[0], "-"))
       &&($acc_lang['q']>$max_q)) {
      $chosen_lang=$acc_lang[0];
      $max_q=$acc_lang['q'];
    }
  }

  return $chosen_lang;
}

if(isset($_REQUEST['ui_lang']))
  $ui_lang=$_REQUEST['ui_lang'];
if(!isset($ui_lang)&&
   array_key_exists('param', $_REQUEST)&&
   array_key_exists('ui_lang', $_REQUEST['param']))
  $ui_lang=$_REQUEST['param']['ui_lang'];
if(!isset($ui_lang)&&array_key_exists('ui_lang', $_COOKIE))
  $ui_lang=$_COOKIE['ui_lang'];
if(!isset($ui_lang))
  $ui_lang=lang_from_browser($ui_langs);
if(!$ui_lang)
  $ui_lang="en";

if(isset($_REQUEST['data_lang']))
  $data_lang=$_REQUEST['data_lang'];
if(!isset($data_lang)&&
   array_key_exists('param', $_REQUEST)&&
   array_key_exists('data_lang', $_REQUEST['param']))
  $data_lang=$_REQUEST['param']['data_lang'];
if(!isset($data_lang)&&array_key_exists('data_lang', $_COOKIE))
  $data_lang=$_COOKIE['data_lang'];
if(!isset($data_lang))
  $data_lang="auto";
if($data_lang=="auto")
  $data_lang=$ui_lang;

function lang_load($lang, $loaded=array()) {
  global $lang_str;
  global $plugins_list;

  $lang_str=array();

  @include_once("lang/{$lang}.php");
  @include_once("lang/lang_{$lang}.php");
  @include_once("lang/tags_{$lang}.php");
  foreach($plugins_list as $plugin=>$dummy) {
    @include_once("plugins/$plugin/lang_{$lang}.php");
  }
  $loaded[]=$lang;

  if(!isset($lang_str['base_language']))
    $lang_str['base_language']="en";
  if(in_array($lang_str['base_language'], $loaded))
    return;

  $save_lang_str=$lang_str;
  lang_load($lang_str['base_language'], $loaded);
  $lang_str=array_merge($lang_str, $save_lang_str);
}

function lang_code_check($lang) {
  return preg_match("/^[a-z\-]+$/", $lang);
}

function lang_init() {
  global $lang_str;
  global $ui_lang;
  global $ui_langs;
  global $data_lang;
  global $language_list;
  global $design_hidden;
  global $lang_genders;
  global $version_string;

  lang_load($ui_lang);

  // Define a language string for every language
  foreach($language_list as $abbr=>$lang) {
    $lang_str["lang_native:".$abbr]=$lang;
  }

  html_export_var(array("ui_lang"=>$ui_lang, "data_lang"=>$data_lang, "ui_langs"=>$ui_langs, "lang_str"=>$lang_str, "language_list"=>$language_list, "lang_genders"=>$lang_genders));
  add_html_header("<meta http-equiv=\"content-language\" content=\"{$ui_lang}\">");
}
