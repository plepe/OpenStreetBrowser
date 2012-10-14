<?
include "www/lang/list.php";
$lang_str=array();
$f=fopen("www/lang/tags_en.php", "r");
$current_tag=false;
while($r=fgets($f)) {
  if(preg_match("/^#?\\\$lang_str\[[\"'](tag:[^\"'=]*)(=[^\"']*)?[\"']\]=(.*);$/", $r, $m)) {
    $lang_str_en["$m[1]$m[2]"]=$m[3];
  }
}
fclose($f);

foreach($ui_langs as $lang) {
  if($lang=="en")
    continue;

  $lang_str=array();
  @include "www/lang/tags_{$lang}.php";

  foreach($lang_str as $str=>$d) {
    if(!isset($lang_str_en[$str])) {
      $not_defined[$str]=$d;
    }
  }
}

$f=fopen("www/lang/tags_en.php", "r");
$current_tag=false;
$ret="";
while($r=fgets($f)) {
  if(preg_match("/^#?\\\$lang_str\[[\"'](tag:[^\"'=]*)(=[^\"']*)?[\"']\]=(.*);$/", $r, $m)) {
    $current_tag=$m[1];
  }
  else {
    foreach($not_defined as $str=>$d) {
      if(substr($str, 0, strlen($current_tag)+1)=="$current_tag=") {
	$ret.="#\$lang_str[\"$str\"]=\"\";\n";
      }
    }

    $current_tag=false;
  }


  $ret.=$r;
}
fclose($f);

file_put_contents("www/lang/tags_en.php", $ret);
