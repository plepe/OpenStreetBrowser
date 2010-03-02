<?
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
  elseif(!$lang_str[$key]) {
    if(ereg("/(.*)$", $key, $m))
      $key=$m[1];

    return $key.(sizeof($params)?" ".implode(", ", $params):"");
  }
  else {
    $l=$lang_str[$key];
  }

  if(is_array($l)) {
    $count--;
    if(!isset($l[$count]))
      $count=1;
    $l=$l[$count];
  }

  return vsprintf($l, $params);
}

$available_languages=array(
  ""=>"Default Language",
  "en"=>"English",
  "de"=>"Deutsch",
  "bg"=>"Български"
);

function show_lang_select() {
  global  $available_languages;

  print "<div id='lang_select'>\n";;
  print "<form id='lang_select_form' action='' method='get'>\n";
  print "<select id='lang' name='lang' onChange='change_language()'>\n";
  foreach($available_languages as $k=>$name) {
     print "<option value='$k'";
     if($_REQUEST[lang]==$k)
       print " selected='selected'";
     print ">$name</option>\n";
  }
  print "</select>\n";
  print "</form>\n";
  print "</div>\n";
}

$lang=$_REQUEST[lang];
if(!$lang)
  $lang=$_REQUEST["option:ui_lang"];
if($_REQUEST[param][lang])
  $lang=$_REQUEST[param][lang];
if(!$lang)
  $lang="en";

require_once("lang/$lang.php");
if(!$design_hidden) {
  print "<script type='text/javascript' src='inc/lang.js'></script>\n";
  print "<script id='language_js' type='text/javascript' src='lang/en.js'></script>\n";
}

if($lang) {
  require_once("lang/$lang.php");
  if(!$design_hidden)
    print "<script type='text/javascript' src='lang/$lang.js'></script>\n";
}

html_export_var(array("lang"=>$lang));
