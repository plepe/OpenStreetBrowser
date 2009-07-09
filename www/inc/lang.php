<?
function lang() {
  global $lang_str;

  $key=func_get_arg(0);
  $params=array_slice(func_get_args(), 1);

  if(!$lang_str[$key])
    return $key.(sizeof($params)?" ".implode(", ", $params):"");

  return vsprintf($lang_str[$key], $params);
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
if($_REQUEST[param][lang])
  $lang=$_REQUEST[param][lang];

require_once("lang/en.php");
if($lang) {
  require_once("lang/$lang.php");
}
