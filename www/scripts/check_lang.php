<? Header("content-type: text/html; charset=utf-8"); ?>
<html>
<head>
<title>OpenStreetBrowser - Language Check</title>
</head>
<body>
<?
require "../../conf.php";

$verbose=false;

$ui_lang=$_REQUEST['ui_lang'];
if(!$ui_lang) {
  include "$root_path/www/lang/list.php";

  print "The following languages are defined:<ul>\n";
  foreach($ui_langs as $ui_lang) {
    print "<li> <a href='check_lang.php?ui_lang={$ui_lang}'>{$ui_lang}: {$language_list[$ui_lang]}</a>\n";
  }
  print "</ul>\n";
  exit;
}

function lang() {
}

function compare_lang_file($src, $dst) {
  global $root_path;
  print "<h3>Check file <i>$src</i> to <i>$dst</i></h3>\n";

  $lang_str=array();
  @include "$root_path/$src";
  $lang_str_src=$lang_str;

  $lang_str=array();
  @include "$root_path/$dst";
  $lang_str_dst=$lang_str;

  $missing=array();
  $same=array();
  foreach($lang_str_src as $k=>$v) {
    if(!isset($lang_str_dst[$k])) {
      $missing[]=$k;
    }
    else if($lang_str_dst[$k]==$lang_str_src[$k]) {
      $same[]=$k;
    }
  }

  $deprecated=array();
  foreach($lang_str_dst as $k=>$v) {
    if(!isset($lang_str_src[$k])) {
      $deprecated[]=$k;
    }
  }

  print "<li> <b>There's a total of ".sizeof($lang_str_src)." strings in English version:</b><br>\n";
  print implode(", ", array_keys($lang_str_src))."\n";

  print "<li> <b>There's a total of ".sizeof($lang_str_dst)." strings in the translation.</b><br>\n";
  print implode(", ", array_keys($lang_str_dst))."\n";

  print "<li> <b>".sizeof($missing)." Strings missing:</b><br>\n";
  print implode(", ", $missing)."\n";

  print "<li> <b>".sizeof($deprecated)." Strings deprecated (defined in translation, but not in English):</b><br>\n";
  print implode(", ", $deprecated)."\n";

  print "<li> <b>".sizeof($same)." Strings not translated (or translation equal to English):</b><br>\n";
  print implode(", ", $same)."\n";
}

compare_lang_file("www/lang/en.php", "www/lang/{$ui_lang}.php");
compare_lang_file("www/lang/tags_en.php", "www/lang/tags_{$ui_lang}.php");
$d=opendir("$root_path/www/plugins");
while($f=readdir($d)) {
  if(file_exists("$root_path/www/plugins/$f/lang_en.php")||
     file_exists("$root_path/www/plugins/$f/lang_{$ui_lang}.php"))
    compare_lang_file("www/plugins/$f/lang_en.php", "www/plugins/$f/lang_{$ui_lang}.php");
}
closedir($d);
$lang_str_src=$lang_str;

$lang_str=array();
@include "$root_path/www/lang/{$ui_lang}.php";
@include "$root_path/www/lang/tags_{$ui_lang}.php";
$d=opendir("$root_path/www/plugins");
while($f=readdir($d))
  @include "$root_path/www/plugins/$f/lang_{$ui_lang}.php";
closedir($d);
$lang_str_dst=$lang_str;

?>
</body>
</html>
