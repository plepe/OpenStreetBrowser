<?
function lang() {
  global $lang_str;
  $offset=1;

  $key=func_get_arg(0);
  if(is_integer(func_get_arg(1))) {
    $offset++;
    $count=func_get_arg(1);
  }
  else
    $count=1;
  $params=array_slice(func_get_args(), $offset);

  if(!$lang_str[$key])
    return $key.(sizeof($params)?" ".implode(", ", $params):"");

  $l=$lang_str[$key];
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
if($_REQUEST[param][lang])
  $lang=$_REQUEST[param][lang];

require_once("lang/en.php");
if($lang) {
  require_once("lang/$lang.php");
}
