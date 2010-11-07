<?
require "../../conf.php";

$verbose=false;

$ui_lang=$_REQUEST['ui_lang'];
if(!$ui_lang)
  $ui_lang="en";

function lang() {
}

$lang_str=array();
@include "$root_path/www/lang/en.php";
@include "$root_path/www/lang/tags_en.php";
$d=opendir("$root_path/www/plugins");
while($f=readdir($d))
  @include "$root_path/www/plugins/$f/lang_en.php";
closedir($d);
$en_lang=$lang_str;

$lang_str=array();
@include "$root_path/www/lang/{$ui_lang}.php";
@include "$root_path/www/lang/tags_{$ui_lang}.php";
$d=opendir("$root_path/www/plugins");
while($f=readdir($d))
  @include "$root_path/www/plugins/$f/lang_{$ui_lang}.php";
closedir($d);
$ui_lang_lang=$lang_str;

$missing=array();
$same=array();
foreach($en_lang as $k=>$v) {
  if(!isset($ui_lang_lang[$k])) {
    $missing[]=$k;
  }
  else if($ui_lang_lang[$k]==$en_lang[$k]) {
    $same[]=$k;
  }
}

$deprecated=array();
foreach($ui_lang_lang as $k=>$v) {
  if(!isset($en_lang[$k])) {
    $deprecated[]=$k;
  }
}

print "<li> <b>There's a total of ".sizeof($en_lang)." strings in English version:</b><br>\n";
print implode(", ", array_keys($en_lang))."\n";

print "<li> <b>There's a total of ".sizeof($ui_lang_lang)." strings in the translation.</b><br>\n";
print implode(", ", array_keys($ui_lang_lang))."\n";

print "<li> <b>".sizeof($missing)." Strings missing:</b><br>\n";
print implode(", ", $missing)."\n";

print "<li> <b>".sizeof($deprecated)." Strings deprecated (defined in translation, but not in English):</b><br>\n";
print implode(", ", $deprecated)."\n";

print "<li> <b>".sizeof($same)." Strings not translated (or translation equal to English):</b><br>\n";
print implode(", ", $same)."\n";
