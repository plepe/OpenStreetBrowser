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

function translation_read_file_php($lang, $f) {
  $ret=array();
  global $translation_path;
  $file="$translation_path/$f$lang.php";

  if(!file_exists($file))
    return null;

  include "$file";

  $f=fopen($file, "r");
  while($r=fgets($f)) {
    if(preg_match("/^( *)\\\$lang_str\[['\"]([^\"]*)['\"]\]/", $r, $m)) {
      $found=false;
      $ret[$m[2]]=array('value'=>$lang_str[$m[2]]);
      if(preg_match("/\\/\\/\s*(.*)$/", $r, $m1)) {
	$ret[$m[2]]['help']=$m1[1];
      }
    }
  }
  fclose($f);

  return array(
    'list'=>$ret,
    'order'=>array_keys($ret),
  );
}

function translation_read_file_doc($lang, $f) {
  $ret=array();
  global $translation_path;
  $file="$translation_path/$f$lang.doc";

  if(!file_exists($file))
    return "";

  return array(
    'contents'=>file_get_contents($file),
  );
}

function translation_read_file_category($lang, $f) {
  $ret=array();
  if(!preg_match("/^(.*):(.*)$/", $f, $m))
    return null;

  $category=$m[1];
  $version=$m[2];

  $res=sql_query("select * from category where version='$version'", $db_central);
  $elem=pg_fetch_assoc($res);
  $tags=parse_hstore($elem['tags']);
  $orig_lang=$tags['lang'];
  if(!$orig_lang)
    $orig_lang="en";

  $ret["$category:name"]=array(
    'value'=>$tags["name:$lang"],
    'help'=>"Category name",
  );
  $ret["$category:description"]=array(
    'value'=>$tags["description:$lang"],
    'help'=>"Category description",
  );

  $res_rule=sql_query("select * from category_rule where category_id='$category' and version='$version'", $db_central);
  while($elem_rule=pg_fetch_assoc($res_rule)) {
    $tags=parse_hstore($elem_rule['tags']);
    $rule_id=$elem_rule['rule_id'];

    $ret["$category:$rule_id:name"]=array(
      'value'=>($lang==$orig_lang?$tags["name"]:$tags["name:$lang"]),
      'help'=>"Match: {$tags['match']}",
    );
    $ret["$category:$rule_id:description"]=array(
      'value'=>($lang==$orig_lang?$tags["description"]:$tags["description:$lang"]),
    );
  }

  return array(
    'list'=>$ret,
    'help'=>"Please use the form =array([Gender,] \"Singular\", \"Plural\") where appropriate (see top of page for explanation)",
    'orig_lang'=>$orig_lang,
    'order'=>array_keys($ret),
  );
}

function translation_read_file($lang, $f) {
  global $translation_path;

  if(preg_match("/^(php|doc|category):(.*)$/", $f, $m)) {
    $mode=$m[1];
    $file=$m[2];
  }

  switch($mode) {
    case "php":
      return translation_read_file_php($lang, $file);
    case "doc":
      return translation_read_file_doc($lang, $file);
    case "category":
      return translation_read_file_category($lang, $file);
  }
}

function translation_read($lang, $files=null) {
  $ret=array();
  if(!$files)
    $files=translation_files_list();

  foreach($files as $f) {
    $ret[$f]=translation_read_file($lang, $f);
  }

  return $ret;
}

function ajax_translation_read($param) {
  return translation_read($param['lang'], $param['files']);
}

function translation_main_links($links) {
  $links[]=array(5, "<a href='javascript:translation_open()'>".lang("translation:name")."</a>");
}

register_hook("main_links", "translation_main_links");
