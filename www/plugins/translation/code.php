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

function translation_files_list() {
  global $translation_path;

  translation_init();

  $ret=array();

  // $lang_str-Files and doc-Files
  $ret[]="php:www/lang/";
  $ret[]="php:www/lang/tags_";
  $d=opendir("$translation_path/www/plugins");
  while($f=readdir($d)) {
    $d1=opendir("$translation_path/www/plugins/$f");
    while($f1=readdir($d1)) {
      if(preg_match("/^(.*_)en.doc$/", $f1, $m))
	$ret[]="doc:www/plugins/$f/$m[1]";
    }
    closedir($d1);

    if(file_exists("$translation_path/www/plugins/$f/lang_en.php"))
      $ret[]="php:www/plugins/$f/lang_";
  }
  closedir($d);

  // Categories
  $res=sql_query("select * from category_current", $db_central);
  while($elem=pg_fetch_assoc($res)) {
    $ret[]="category:{$elem['category_id']}:{$elem['version']}";
  }

  return $ret;
}

function ajax_translation_files_list() {
  return translation_files_list();
}

function translation_main_links($links) {
  $links[]=array(5, "<a href='javascript:translation_open()'>".lang("translation:name")."</a>");
}

register_hook("main_links", "translation_main_links");
