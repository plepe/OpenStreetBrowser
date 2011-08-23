<?
include "lang/list.php";
define("F", 1);
define("M", 2);
define("N", 3);

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
    if(ereg("/(.*)$", $key, $m))
      $key=$m[1];
    elseif(preg_match("/^tag:([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/", $key, $m)) {
      if(isset($lang_str["tag:{$m[1]}"]))
        $key=lang("tag:{$m[1]}")."{$m[2]}{$m[3]}";
      else
        $key="{$m[1]}{$m[2]}{$m[3]}";
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
  $data_lang=lang_from_browser();
if(!isset($data_lang))
  $data_lang="";

function lang_init() {
  global $lang_str;
  global $ui_lang;
  global $ui_langs;
  global $data_lang;
  global $language_list;
  global $plugins_list;
  global $design_hidden;

  // Define a language string for every language
  foreach($language_list as $abbr=>$lang) {
    $lang_str["lang:".$abbr]=$lang;
    $lang_str["lang_native:".$abbr]=$lang;
  }

  @include_once("lang/en.php");
  @include_once("lang/tags_en.php");
  if($ui_lang&&($ui_lang!="en")) {
    @include_once("lang/{$ui_lang}.php");
    @include_once("lang/tags_{$ui_lang}.php");
  }

  foreach($plugins_list as $plugin=>$dummy) {
    @include_once("plugins/$plugin/lang_en.php");
    @include_once("plugins/$plugin/lang_{$ui_lang}.php");
  }

  if(!$design_hidden)
    print "<script type='text/javascript' src='inc/lang.js'></script>\n";

  html_export_var(array("ui_lang"=>$ui_lang, "data_lang"=>$data_lang, "ui_langs"=>$ui_langs, "lang_str"=>$lang_str, "language_list"=>$language_list));
  add_html_header("<meta http-equiv=\"content-language\" content=\"{$ui_lang}\">");
}

// DEPRECATED: include JS language file
function lang_html_end() {
  global $design_hidden;
  global $ui_lang;

  if((!$design_hidden)&&(file_exists("lang/{$ui_lang}.js")))
    print "<script type='text/javascript' src='lang/{$ui_lang}.js'></script>\n";
}
// DEPRECATED: remove when removing function lang_html_done
register_hook("html_end", "lang_html_end");
