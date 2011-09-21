<?
global $translation_path;

function translation_init() {
  global $root_path;
  global $translation_path;
  $translation_path="$root_path/data/translation";

  if(!file_exists("$translation_path")) {
    mkdir("$translation_path");
    chdir("$translation_path");
    system("git clone $root_path $translation_path");
  }
}

function ajax_translation_files_list() {
  global $translation_path;

  translation_init();

  $ret=array();
  $ret[]="php:www/lang/";
  $ret[]="php:www/lang/tags_";
  $d=opendir("$translation_path/www/plugins");
  while($f=readdir($d)) {
    if(file_exists("$translation_path/www/plugins/$f/lang_en.php"))
      $ret[]="php:www/plugins/$f/lang_";
  }
  closedir($d);

  return $ret;
}

function translation_main_links($links) {
  $links[]=array(5, "<a href='javascript:translation_open()'>".lang("translation:name")."</a>");
}

register_hook("main_links", "translation_main_links");
