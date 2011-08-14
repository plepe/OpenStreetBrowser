#!/usr/bin/php
<?
if(sizeof($argv)!=4) {
  print "Usage: move_lang_str.php lang_str src_file dest_file\n".
        "  lang_str may be a regexp, e.g. \"wikipedia:.*\"\n".
	"  src_file and dest_file are the file_paths stopping before the language code, e.g. \"www/lang/\" or \"www/plugins/wikipedia/lang_\"\n";
  exit;
}
$match=$argv[1];
$src_files=$argv[2];
$dst_files=$argv[3];

function lang_get_files($prefix) {
  $ret=array();

  if(!preg_match("/^(.*\/)([^\/]*)$/", $prefix, $m)) {
    print "Could not parse $prefix\n";
    exit;
  }

  $d=opendir($m[1]);
  while($f=readdir($d)) {
    if(substr($f, 0, strlen($m[2]))==$m[2]) {
      $suffix=substr($f, strlen($m[2]));
      if(preg_match("/^([a-z]{2,3}(-[a-z]+)?+)\./", $suffix, $m1)) {
	$ret[$m1[1]]="$m[1]$f";
      }
    }
  }
  closedir($d);

  return $ret;
}

$src_files=lang_get_files($argv[2]);
$match="/\[['\"]{$match}['\"]\]/";

foreach($src_files as $lang_code=>$file) {
  $src=file_get_contents($file);
  if(!file_exists("{$dst_files}{$lang_code}.php"))
    $dst="<?\n";
  else
    $dst=file_get_contents("{$dst_files}{$lang_code}.php");

  $src=explode("\n", $src);
  $dst=explode("\n", $dst);
  $src_after=array();

  foreach($src as $src_line) {
    if(preg_match($match, $src_line, $m)) {
      $dst[]=$src_line;
    }
    else {
      $src_after[]=$src_line;
    }
  }

  file_put_contents($file, implode("\n", $src_after));
  file_put_contents("{$dst_files}{$lang_code}.php", implode("\n", $dst));
}

