#!/usr/bin/php
<?
if(sizeof($argv)!=4) {
  print "Usage: rename_lang_str.php src_lang_str dst_lang_str file\n".
        "  src_lang_str may be a regexp, e.g. 'wikipedia:(.*)'\n".
        "  dst_lang_str can include references like \$1 for the first pattern, e.g. 'foo:$1'\n".
        "    put the strings in '...'\n".
	"  file is the file_path stopping before the language code, e.g. \"www/lang/\" or \"www/plugins/wikipedia/lang_\"\n";
  exit;
}
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

$match=$argv[1];
$replacement=$argv[2];
$src_files=lang_get_files($argv[3]);
$match="/\[['\"]{$match}['\"]\]/";
$replacement="['$replacement']";

print "Replacing: $match -> $replacement\n";

foreach($src_files as $lang_code=>$file) {
  $src=file_get_contents($file);
  $src_after=array();

  $src=explode("\n", $src);

  foreach($src as $src_line) {
    if(($str=preg_replace($match, $replacement, $src_line))) {
      $src_after[]=$str;
    }
    else {
      $src_after[]=$src_line;
    }
  }

  file_put_contents($file, implode("\n", $src_after));
}
